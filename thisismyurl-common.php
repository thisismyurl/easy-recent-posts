<?php
/**
 * Easy Recent Posts — common base class.
 *
 * @package   Easy_Recent_Posts
 * @copyright Copyright (c) 2008, Christopher Ross
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, v2 (or later)
 * @since     15.01
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Thisismyurl_Common_EREP' ) ) {
	/**
	 * Base class: text domain, enqueue, admin menu, settings page.
	 *
	 * @since 14.11
	 */
	class Thisismyurl_Common_EREP {

		/**
		 * Constructor — register all core hooks.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'plugin_action_links_' . THISISMYURL_EREP_FILENAME, array( $this, 'add_action_link' ), 10, 2 );
		}

		/**
		 * Load plugin text domain.
		 */
		public function load_textdomain() {
			load_plugin_textdomain(
				'easy-recent-posts',
				false,
				plugin_dir_path( __FILE__ ) . 'languages'
			);
		}

		/**
		 * Enqueue front-end stylesheet if present.
		 */
		public function enqueue_style() {
			$css_file = plugin_dir_path( __FILE__ ) . 'css/easy-recent-posts.css';
			if ( file_exists( $css_file ) ) {
				wp_enqueue_style(
					'easy-recent-posts',
					THISISMYURL_EREP_URL . 'css/easy-recent-posts.css',
					array(),
					THISISMYURL_EREP_VERSION
				);
			}
		}

		/**
		 * Enqueue admin stylesheet on the plugin settings page.
		 */
		public function admin_enqueue_scripts() {
			if ( ! isset( $_GET['page'] ) || 'easy_recent_posts_settings' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}
			wp_enqueue_style(
				'thisismyurl-common-erep',
				THISISMYURL_EREP_URL . 'css/thisismyurl-common.css',
				array(),
				THISISMYURL_EREP_VERSION
			);
		}

		/**
		 * Register the options-page submenu (hidden from the menu — accessible via action link).
		 */
		public function admin_menu() {
			add_options_page(
				esc_html__( 'Easy Recent Posts', 'easy-recent-posts' ),
				esc_html__( 'Easy Recent Posts', 'easy-recent-posts' ),
				'manage_options',
				'easy_recent_posts_settings',
				array( $this, 'settings_page' )
			);
			remove_submenu_page( 'options-general.php', 'easy_recent_posts_settings' );
		}

		/**
		 * Add Settings link to the plugin row on Plugins > Installed Plugins.
		 *
		 * @param string[] $links Existing action links.
		 * @return string[]
		 */
		public function add_action_link( $links ) {
			$links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'options-general.php?page=easy_recent_posts_settings' ) ),
				esc_html__( 'Settings', 'easy-recent-posts' )
			);
			return $links;
		}

		/**
		 * Render the settings/about page.
		 */
		public function settings_page() {
			?>
			<div id="thisismyurl-settings" class="wrap">
				<h1><?php echo esc_html( THISISMYURL_EREP_NAME ); ?></h1>

				<h2><?php esc_html_e( 'General settings', 'easy-recent-posts' ); ?></h2>
				<p>
					<?php
					printf(
						/* translators: %s: URL to readme.txt */
						esc_html__( 'The plugin has no settings. Once activated it works automatically. See the %sreadme.txt%s for full details.', 'easy-recent-posts' ),
						'<a href="' . esc_url( THISISMYURL_EREP_URL . 'readme.txt' ) . '">',
						'</a>'
					);
					?>
				</p>
			</div>

			<div id="donate">
				<h2><?php esc_html_e( 'How to support the software', 'easy-recent-posts' ); ?></h2>
				<p><?php esc_html_e( 'Open source software only works through the hard work of community members volunteering their time. If you would like to show your support, here is how you can help:', 'easy-recent-posts' ); ?></p>
				<ul>
					<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/' . THISISMYURL_EREP_NAMESPACE . '/' ); ?>"><?php esc_html_e( 'Give it a great review on WordPress.org', 'easy-recent-posts' ); ?></a></li>
					<li><a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/' . THISISMYURL_EREP_NAMESPACE ); ?>"><?php esc_html_e( 'Offer support in the plugin forums', 'easy-recent-posts' ); ?></a></li>
					<li><a href="<?php echo esc_url( 'https://github.com/thisismyurl/' . THISISMYURL_EREP_NAMESPACE . '/issues' ); ?>"><?php esc_html_e( 'Report an issue or suggest a feature', 'easy-recent-posts' ); ?></a></li>
					<li><a href="https://github.com/sponsors/thisismyurl"><?php esc_html_e( 'Sponsor the developer on GitHub', 'easy-recent-posts' ); ?></a></li>
				</ul>
				<p>&#8212;&nbsp;<a href="https://thisismyurl.com/"><?php esc_html_e( 'Christopher Ross', 'easy-recent-posts' ); ?></a></p>
			</div>
			<?php
		}
	}
}
