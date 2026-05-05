<?php
/**
 * Plugin Name: Easy Recent Posts
 * Plugin URI:  https://thisismyurl.com/downloads/easy-recent-posts/
 * Description: An easy-to-use WordPress widget and shortcode to add recent posts to any theme.
 * Author:      Christopher Ross
 * Author URI:  https://thisismyurl.com/
 * Version:     26.05.0
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
define( 'THISISMYURL_EREP_SHORTNAME', 'Easy Recent Posts' );
define( 'THISISMYURL_EREP_VERSION',   '26.05.0' );
define( 'THISISMYURL_EREP_FILENAME',  plugin_basename( __FILE__ ) );
define( 'THISISMYURL_EREP_FILEPATH',  plugin_dir_path( __FILE__ ) );
define( 'THISISMYURL_EREP_URL',       plugin_dir_url( __FILE__ ) );
define( 'THISISMYURL_EREP_NAMESPACE', 'easy-recent-posts' );

require_once plugin_dir_path( __FILE__ ) . 'thisismyurl-common.php';

if ( ! class_exists( 'Thisismyurl_Easy_Recent_Posts' ) ) {
	/**
	 * Main plugin class.
	 *
	 * @since 15.01
	 */
	class Thisismyurl_Easy_Recent_Posts extends Thisismyurl_Common_EREP {

		/**
		 * Register hooks.
		 */
		public function run() {
			add_action( 'widgets_init', array( $this, 'widget_init' ) );
			add_shortcode( 'thisismyurl_easy_recent_posts', array( $this, 'easy_recent_posts_shortcode' ) );
		}

		/**
		 * Shortcode handler.
		 */
		public function easy_recent_posts_shortcode() {
			$recent_posts = $this->easy_recent_posts();
			if ( ! empty( $recent_posts ) ) {
				// Output is escaped per-element inside easy_recent_posts().
				echo '<ul class="thisismyurl-easy-recent-posts">' . $recent_posts . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Retrieve and format recent posts.
		 *
		 * @param array|null $options Override defaults. When $options['show'] === 0 (default)
		 *                            returns the HTML string; when non-zero, echoes directly.
		 * @return string|void
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
						get_the_post_thumbnail( $recent_post->ID, 'thumbnail' ),
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
				} else {
					return $html;
				}
			}
		}

		/**
		 * Return default options.
		 *
		 * @return array
		 */
		public function recent_posts_defaults() {
			return array(
				'title'          => __( 'Easy Recent Posts', 'easy-recent-posts' ),
				'post_count'     => 10,
				'include_link'   => 1,
				'before'         => '<li>',
				'after'          => '</li>',
				'nofollow'       => 0,
				'show_excerpt'   => 0,
				'feature_image'  => 0,
				'show_credit'    => 1,
				'show'           => 0,
			);
		}

		/**
		 * Register the widget.
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
