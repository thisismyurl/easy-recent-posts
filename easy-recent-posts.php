<?php
/**
 * Plugin Name: This Is My URL - Easy Recent Posts
 * Plugin URI:  https://thisismyurl.com/downloads/easy-recent-posts/
 * Description: An easy-to-use WordPress widget and shortcode to add recent posts to any theme.
 * Author:      Christopher Ross
 * Author URI:  https://thisismyurl.com/
 * Version:     26.6147
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: easy-recent-posts
 * Domain Path: /languages
 *
 * @package Easy_Recent_Posts
 * @copyright Copyright (c) 2008, Christopher Ross
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'THISISMYURL_EREP_NAME',      'Easy Recent Posts' );
define( 'THISISMYURL_EREP_VERSION',   '26.6147' );
define( 'THISISMYURL_EREP_FILENAME',  plugin_basename( __FILE__ ) );
define( 'THISISMYURL_EREP_FILEPATH',  plugin_dir_path( __FILE__ ) );
define( 'THISISMYURL_EREP_URL',       plugin_dir_url( __FILE__ ) );
define( 'THISISMYURL_EREP_NAMESPACE', 'easy-recent-posts' );

if ( ! class_exists( 'Thisismyurl_Easy_Recent_Posts' ) ) {
	/**
	 * Main plugin class.
	 *
	 * @since 15.01
	 */
	class Thisismyurl_Easy_Recent_Posts {

		/**
		 * Constructor — register shared plugin hooks.
		 *
		 * @since 26.05.0
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
		 *
		 * @since 26.05.0
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'easy-recent-posts' );
		}

		/**
		 * Enqueue front-end stylesheet if present.
		 *
		 * @since 26.05.0
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
		 * Enqueue admin stylesheet on the plugin settings page only.
		 *
		 * @since 26.05.0
		 */
		public function admin_enqueue_scripts() {
			$screen = get_current_screen();
			if ( ! $screen || 'settings_page_easy_recent_posts_settings' !== $screen->id ) {
				return;
			}
			wp_enqueue_style(
				'thisismyurl-common',
				THISISMYURL_EREP_URL . 'css/thisismyurl-common.css',
				array(),
				THISISMYURL_EREP_VERSION
			);
		}

		/**
		 * Register the options-page submenu (hidden — accessible via action link).
		 *
		 * @since 26.05.0
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
		 * Add Settings link to plugin row.
		 *
		 * @since 26.05.0
		 * @param string[] $links Existing action links.
		 * @return string[]
		 */
		public function add_action_link( $links ) {
			$links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'options-general.php?page=easy_recent_posts_settings' ) ),
				esc_html__( 'Settings', 'easy-recent-posts' )
			);
			$links[] = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( 'https://github.com/sponsors/thisismyurl' ),
				esc_html__( 'Sponsor', 'easy-recent-posts' )
			);
			return $links;
		}

		/**
		 * Render settings/about page.
		 *
		 * @since 26.05.0
		 */
		public function settings_page() {
			?>
			<div id="thisismyurl-settings" class="wrap">
				<h1><?php echo esc_html( THISISMYURL_EREP_NAME ); ?></h1>

				<h2><?php esc_html_e( 'General settings', 'easy-recent-posts' ); ?></h2>
				<p>
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: opening <a> tag linking to readme.txt, 2: closing </a> tag */
							__( 'The plugin has no settings. Once activated it works automatically. See the %1$sreadme.txt%2$s for full details.', 'easy-recent-posts' ),
							'<a href="' . esc_url( THISISMYURL_EREP_URL . 'readme.txt' ) . '">',
							'</a>'
						)
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

		/**
		 * Register hooks.
		 *
		 * @since 15.01
		 */
		public function run() {
			add_action( 'widgets_init', array( $this, 'widget_init' ) );
			add_shortcode( 'thisismyurl_easy_recent_posts', array( $this, 'easy_recent_posts_shortcode' ) );
		}

		/**
		 * Shortcode handler — must return, not echo, so WP places output inline.
		 *
		 * @since 15.01
		 * @return string
		 */
		public function easy_recent_posts_shortcode() {
			$recent_posts = $this->easy_recent_posts();
			if ( ! empty( $recent_posts ) ) {
				return '<ul class="thisismyurl-easy-recent-posts">' . $recent_posts . '</ul>';
			}
			return '';
		}

		/**
		 * Retrieve and format recent posts.
		 *
		 * @since 15.01
		 * @param array|null $options Override defaults. When $options['show'] === 0 (default)
		 *                            returns the HTML string; when non-zero, echoes directly.
		 *                            Note: 'before' and 'after' are developer-controlled values
		 *                            (widget update() never persists them).
		 * @return string
		 */
		public function easy_recent_posts( $options = null ) {
			$options = wp_parse_args( $options, $this->recent_posts_defaults() );

			$args = array(
				'posts_per_page' => absint( $options['post_count'] ),
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			$posts  = get_posts( $args );
			$output = array();

			foreach ( $posts as $recent_post ) {
				$item = sprintf(
					'<span class="title">%s</span>',
					esc_html( get_the_title( $recent_post->ID ) )
				);

				if ( 1 === (int) $options['include_link'] ) {
					$rel  = ( 1 === (int) $options['nofollow'] ) ? ' rel="nofollow noopener noreferrer"' : '';
					$item = sprintf(
						'<span class="title-link"><a href="%s" title="%s"%s>%s</a></span>',
						esc_url( get_permalink( $recent_post->ID ) ),
						esc_attr( get_the_title( $recent_post->ID ) ),
						$rel,
						$item
					);
				}

				if ( 1 === (int) $options['feature_image'] && has_post_thumbnail( $recent_post->ID ) ) {
					$item = sprintf(
						'<div class="thumbnail">%s</div>%s',
						get_the_post_thumbnail( $recent_post->ID, 'thumbnail' ), // Escaped internally by WP core.
						$item
					);
				}

				if ( 1 === (int) $options['show_excerpt'] && ! empty( $recent_post->post_excerpt ) ) {
					$item = sprintf(
						'%s<div class="excerpt">%s</div>',
						$item,
						esc_html( $recent_post->post_excerpt )
					);
				}

				$output[] = $options['before'] . $item . $options['after'];
			}

			if ( ! empty( $output ) ) {
				$html = implode( '', $output );
				if ( 0 !== (int) $options['show'] ) {
					echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					return '';
				}
				return $html;
			}

			return '';
		}

		/**
		 * Return default options.
		 *
		 * @since 15.01
		 * @return array
		 */
		public function recent_posts_defaults() {
			return array(
				'title'         => __( 'Easy Recent Posts', 'easy-recent-posts' ),
				'post_count'    => 10,
				'include_link'  => 1,
				'before'        => '<li>',
				'after'         => '</li>',
				'nofollow'      => 0,
				'show_excerpt'  => 0,
				'feature_image' => 0,
				'show_credit'   => 1,
				'show'          => 0,
			);
		}

		/**
		 * Register the widget.
		 *
		 * @since 15.01
		 */
		public function widget_init() {
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-thisismyurl-easy-recent-posts-widget.php';
			register_widget( 'Thisismyurl_Easy_Recent_Posts_Widget' );
		}
	}
}

global $thisismyurl_easy_recent_posts;
$thisismyurl_easy_recent_posts = new Thisismyurl_Easy_Recent_Posts();
$thisismyurl_easy_recent_posts->run();

if ( ! function_exists( 'thisismyurl_easy_recent_posts' ) ) {
	/**
	 * Template tag for theme files.
	 *
	 * @since 15.01
	 * @param array|null $options See Thisismyurl_Easy_Recent_Posts::recent_posts_defaults().
	 */
	function thisismyurl_easy_recent_posts( $options = null ) {
		global $thisismyurl_easy_recent_posts;
		$options = wp_parse_args(
			(array) $options,
			array_merge( $thisismyurl_easy_recent_posts->recent_posts_defaults(), array( 'show' => 1 ) )
		);
		$thisismyurl_easy_recent_posts->easy_recent_posts( $options );
	}
}
