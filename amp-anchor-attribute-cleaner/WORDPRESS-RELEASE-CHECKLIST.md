# WordPress Release Checklist

## Before Release

1. Update plugin header version in `amp-anchor-attribute-cleaner.php`.
2. Update `Stable tag` and changelog in `readme.txt`.
3. Run syntax checks:
   - `php -l amp-anchor-attribute-cleaner.php`
   - `php -l uninstall.php`
4. Verify behavior on a staging site:
   - Internal links: `type` and `id` removed.
   - External links: unchanged.
5. Rebuild ZIP package.

## ZIP Packaging

Package only the plugin directory contents:

- `amp-anchor-attribute-cleaner.php`
- `readme.txt`
- `README.md`
- `uninstall.php`
- `SECURITY.md`
- `assets/*`
- `docs/*`

## WordPress.org Submission Notes

1. Ensure `readme.txt` uses WordPress format.
2. Keep plugin code free from obfuscated/minified bundled binaries.
3. Use GPL-compatible licensing only.
4. Keep changelog clear and versioned.
5. Provide fast updates if any issue is reported.
