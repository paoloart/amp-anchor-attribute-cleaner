# Usage and Verification

## Goal

Prevent AMP issues caused by unnecessary attributes (`type`, `id`) in internal link `<a>` tags.

## Quick Verification

1. Open a post that contains an internal link.
2. View the page source or use your browser inspector.
3. Check the `<a>` tag: `type` and `id` must be absent on internal links.
4. Run an AMP validator on the page URL.

## What the Plugin Does Not Do

- It does not modify the database.
- It does not alter external links.
- It does not change other attributes (`target`, `rel`, `class`, etc.).

## Possible Incompatibilities

If another plugin reintroduces `id` or `type` after the `the_content` filter runs, you may need to increase filter priority or add a specific hook where that theme/plugin modifies the markup later in the render pipeline.
