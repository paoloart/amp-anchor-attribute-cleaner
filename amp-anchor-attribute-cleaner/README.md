# AMP Anchor Attribute Cleaner

A minimal WordPress plugin that removes `type` and `id` attributes from internal `<a>` tags in rendered output to reduce AMP validation issues.

Created by [mimir.bot](https://mimir.bot/).

## What It Does

- Hooks into rendered content filters:
  - `the_content`
  - `the_excerpt`
  - `widget_text_content`
  - `term_description`
- For each internal link (same domain or relative URL), removes:
  - `type="..."`
  - `id="..."`
- Does not modify content saved in the database.

Quick example:

- Before: `<a href="/internal-page" type="post" id="123">Read</a>`
- After: `<a href="/internal-page">Read</a>`

External links remain unchanged.

## Installation

1. Create a plugin folder, for example `amp-anchor-attribute-cleaner`.
2. Put `amp-anchor-attribute-cleaner.php` inside it.
3. Upload the folder to `wp-content/plugins/`.
4. Activate it in **Plugins > Installed Plugins**.

## Security Notes

- The plugin exposes no custom endpoints, admin pages, or user input handling.
- Attack surface is minimal because it only applies server-side filters to rendered markup.
- No software can be 100% secure, but this plugin is hardened for low operational risk.

## Logo

Included assets with mimir.bot logo:

- `assets/icon-128x128.png`
- `assets/icon-256x256.png`

## Documentation

- `docs/USAGE-AND-VERIFICATION.md`
- `SECURITY.md`
- `WORDPRESS-RELEASE-CHECKLIST.md`
