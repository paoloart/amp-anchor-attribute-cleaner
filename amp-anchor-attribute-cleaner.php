<?php
/**
 * Plugin Name: AMP Anchor Attribute Cleaner
 * Plugin URI: https://mimir.bot/
 * Description: Removes "type" and "id" attributes from internal anchor tags in rendered content to improve AMP compatibility.
 * Version: 1.1.3
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: mimir.bot
 * Author URI: https://mimir.bot/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: amp-anchor-attribute-cleaner
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

final class AMP_Anchor_Attribute_Cleaner {
	/**
	 * Content filters where anchor attributes should be cleaned.
	 *
	 * @var string[]
	 */
	private const FILTERS = array(
		'the_content',
		'the_excerpt',
		'widget_text_content',
		'term_description',
	);

	/**
	 * Bootstrap plugin hooks.
	 */
	public static function init(): void {
		foreach (self::FILTERS as $filter_name) {
			add_filter($filter_name, array(__CLASS__, 'strip_anchor_attributes'), 99);
		}
	}

	/**
	 * Remove "type" and "id" attributes from <a> tags in HTML content.
	 *
	 * @param mixed $content Filtered content value.
	 * @return mixed
	 */
	public static function strip_anchor_attributes($content) {
		if (!is_string($content) || '' === $content || false === stripos($content, '<a')) {
			return $content;
		}

		if (class_exists('WP_HTML_Tag_Processor')) {
			return self::strip_with_tag_processor($content);
		}

		$result = preg_replace_callback(
			'/<a\b[^>]*>/i',
			array(__CLASS__, 'sanitize_anchor_tag'),
			$content
		);

		return is_string($result) ? $result : $content;
	}

	/**
	 * Remove attributes using WP HTML API when available (more robust than regex).
	 *
	 * @param string $content HTML content.
	 * @return string
	 */
	private static function strip_with_tag_processor(string $content): string {
		$processor = new WP_HTML_Tag_Processor($content);

		while ($processor->next_tag(array('tag_name' => 'A'))) {
			$href = $processor->get_attribute('href');
			if (!is_string($href) || !self::is_internal_href($href)) {
				continue;
			}

			$processor->remove_attribute('type');
			$processor->remove_attribute('id');
		}

		return $processor->get_updated_html();
	}

	/**
	 * Strip target attributes from a single opening anchor tag.
	 *
	 * @param array<int, string> $matches Regex match set.
	 * @return string
	 */
	private static function sanitize_anchor_tag(array $matches): string {
		$tag = $matches[0] ?? '';

		if ('' === $tag) {
			return $tag;
		}

		$href = self::extract_href_from_tag($tag);
		if (null === $href || !self::is_internal_href($href)) {
			return $tag;
		}

		$clean_tag = preg_replace(
			'/\s(?:type|id)\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i',
			'',
			$tag
		);

		return is_string($clean_tag) ? $clean_tag : $tag;
	}

	/**
	 * Extract href value from an opening anchor tag.
	 *
	 * @param string $tag Opening anchor tag.
	 * @return string|null
	 */
	private static function extract_href_from_tag(string $tag): ?string {
		if (!preg_match('/\shref\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $tag, $href_match)) {
			return null;
		}

		$href = '';
		if (!empty($href_match[1])) {
			$href = $href_match[1];
		} elseif (!empty($href_match[2])) {
			$href = $href_match[2];
		} elseif (!empty($href_match[3])) {
			$href = $href_match[3];
		}

		$href = trim((string) html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
		return '' === $href ? null : $href;
	}

	/**
	 * Detect whether href points to an internal URL.
	 *
	 * @param string $href Link href value.
	 * @return bool
	 */
	private static function is_internal_href(string $href): bool {
		$href = trim((string) html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

		if ('' === $href) {
			return false;
		}

		if ('/' === $href[0] || '#' === $href[0] || '?' === $href[0]) {
			return true;
		}

		$scheme = (string) wp_parse_url($href, PHP_URL_SCHEME);
		if ('' !== $scheme && !in_array(strtolower($scheme), array('http', 'https'), true)) {
			return false;
		}

		$link_host = self::normalize_host((string) wp_parse_url($href, PHP_URL_HOST));
		if ('' === $link_host) {
			return true;
		}

		static $site_host = null;
		if (null === $site_host) {
			$site_host = self::normalize_host((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
		}

		return '' !== $site_host && $link_host === $site_host;
	}

	/**
	 * Normalize host for strict same-domain comparison.
	 *
	 * @param string $host URL host.
	 * @return string
	 */
	private static function normalize_host(string $host): string {
		$host = strtolower(trim($host));
		if ('' === $host) {
			return '';
		}

		return preg_replace('/^www\./', '', $host) ?? $host;
	}
}

AMP_Anchor_Attribute_Cleaner::init();
