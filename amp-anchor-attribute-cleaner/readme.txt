=== AMP Anchor Attribute Cleaner ===
Contributors: mimirbot
Tags: amp, links, sanitizer, validation
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.1.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Free WordPress plugin by mimir.bot: removes `type` and `id` from internal `<a>` tags in rendered content to improve AMP compatibility.

== Description ==

Created by **mimir.bot**.

This plugin is intentionally minimal and has no settings, to reduce attack surface and operational complexity:

- No admin settings panel.
- No custom endpoints.
- No user input handling.
- No post database modifications.

It runs only at render time and cleans internal links (same domain or relative URLs) by removing:

- `type="..."`
- `id="..."`

Applied filters:

- `the_content`
- `the_excerpt`
- `widget_text_content`
- `term_description`

Practical example:

- Before: `<a href="/internal-page" type="post" id="123">Read</a>`
- After: `<a href="/internal-page">Read</a>`

External links are left unchanged.

== Assets ==

mimir.bot logo assets included in the package:

- `assets/icon-128x128.png`
- `assets/icon-256x256.png`

== Installation ==

1. In WordPress, go to **Plugins > Add New Plugin**.
2. Click **Upload Plugin**.
3. Select the ZIP file `amp-anchor-attribute-cleaner.zip`.
4. Activate the plugin.

Or install manually:

1. Extract the `amp-anchor-attribute-cleaner` folder.
2. Upload it to `wp-content/plugins/`.
3. Activate the plugin.

== Frequently Asked Questions ==

= Does it modify posts saved in the database? =

No. The plugin does not alter saved content; it only modifies rendered HTML output.

= Does it remove attributes from external links too? =

No. It removes `type` and `id` only from internal links.

= Is it 100% secure? =

No software can be guaranteed 100% secure, but this implementation is hardened: no user input, no custom endpoints, no database writes, and render-time-only sanitization.

== Changelog ==

= 1.1.3 =

- Replaced `parse_url()` with `wp_parse_url()` for WordPress coding standards compatibility.
- Updated `Tested up to` to WordPress 6.9.
- Added `languages/` directory to match `Domain Path` header.
- Moved release checklist file into `docs/` to satisfy plugin root file checks.

= 1.1.2 =

- Added `SECURITY.md` with responsible disclosure policy.
- Added `docs/WORDPRESS-RELEASE-CHECKLIST.md` for submission workflow.
- Added upgrade notice section.

= 1.1.1 =

- WordPress metadata update: added `Domain Path`.
- Compatibility hardening: removed `strict_types` declaration.
- Added `uninstall.php` cleanup stub.
- Documentation filenames switched to fully English naming.

= 1.1.0 =

- Parser hardening: uses WP HTML Tag Processor when available.
- Regex fallback for compatibility with older WordPress versions.
- Metadata updated: created by mimir.bot.

= 1.0.0 =

- First stable release.
- Removal of `type` and `id` attributes from internal links in rendered output.

== Upgrade Notice ==

= 1.1.2 =

Recommended maintenance release with release-process documentation and disclosure policy.
