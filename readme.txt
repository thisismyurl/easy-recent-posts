=== Easy Recent Posts ===
Contributors: christopherross
Tags: recent posts, recent, sidebar, widget
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 26.05.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An easy-to-use WordPress widget and shortcode to add recent posts to any theme.

== Description ==

**Easy Recent Posts** displays your most recently published posts in any sidebar or widget area.

Features:

* Sidebar widget and `[thisismyurl_easy_recent_posts]` shortcode
* Optional featured image, excerpt, and nofollow support
* No external API calls
* Multilingual (en, de_DE, fr_FR, fr_CA)

== Installation ==

1. Upload the plugin to `/wp-content/plugins/`.
2. Activate it through **Plugins > Installed Plugins**.
3. Add the **Easy Recent Posts** widget to a sidebar or use the shortcode `[thisismyurl_easy_recent_posts]`.

== Changelog ==

= 26.05.0 =
* Fixed class-name typo `thissimyurl` → `Thisismyurl_Easy_Recent_Posts` throughout.
* Renamed widget file to match WordPress naming conventions.
* Fixed `wp_parse_args` argument order in `easy_recent_posts()`.
* Fixed `orderby`/`order` separation in `get_posts()` args; added `post_status => publish`.
* Migrated widget to `parent::__construct()` and `$this->get_field_id()` / `$this->get_field_name()`.
* Hardened widget `update()` with `sanitize_text_field` and `absint`.
* Replaced `extract($args)` in widget `widget()` with direct array access.
* Fixed global variable name (`$thissimyurl_EasyRecentPosts` → `$thisismyurl_easy_recent_posts`).
* Renamed `langs/` to `languages/`; fixed `load_plugin_textdomain()` path.
* Added fr_CA translation.
* Updated all HTTP URIs to HTTPS.
* Bumped "Requires at least" to 6.0 and "Requires PHP" to 7.4.

= 15.01 =
* Initial public release.

== Upgrade Notice ==

= 26.05.0 =
This release fixes a class-name typo that affected widget registration on some sites. Deactivate and reactivate the plugin after upgrading.

== Screenshots ==

1. Widget settings panel in the WordPress customiser.

