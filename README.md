# This Is My URL - Easy Recent Posts

Display your most recent posts with a widget or shortcode. Supports featured images, excerpts, and `rel="nofollow"`. No external services required.

[![WordPress Plugin](https://img.shields.io/badge/WordPress-6.0%2B-blue)](https://wordpress.org/plugins/easy-recent-posts/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)](https://php.net/)
[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

## Features

- Widget and `[thisismyurl_easy_recent_posts]` shortcode
- Optional featured images, excerpts, and `rel="nofollow"` support
- Multilingual: English, German, French (France), French (Canada)
- No external API calls

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher

## Installation

1. Upload to `/wp-content/plugins/easy-recent-posts/`.
2. Activate through **Plugins › Installed Plugins**.
3. Add the **Easy Recent Posts** widget to a sidebar, or use the shortcode in any post or page.

## Shortcode

```
[thisismyurl_easy_recent_posts]
```

## Architecture

The plugin now registers lifecycle hooks directly in its main class and no longer relies on the legacy `thisismyurl-common.php` inheritance layer.

## Template Tag

```php
<?php thisismyurl_easy_recent_posts(); ?>
```

## Support

- **WordPress.org forum:** https://wordpress.org/support/plugin/easy-recent-posts/
- **Bug reports / feature requests:** [GitHub Issues](https://github.com/thisismyurl/easy-recent-posts/issues)

## Changelog

See [releases](https://github.com/thisismyurl/easy-recent-posts/releases) or [readme.txt](readme.txt).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).

---

**Author:** [Christopher Ross](https://thisismyurl.com) — WordPress specialist since 2007.  
**Sponsor:** https://github.com/sponsors/thisismyurl


---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*

