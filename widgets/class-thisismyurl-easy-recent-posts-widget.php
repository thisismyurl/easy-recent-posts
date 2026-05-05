<?php
/**
 * Easy Recent Posts — widget class.
 *
 * @package   Easy_Recent_Posts
 * @copyright Copyright (c) 2008, Christopher Ross
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, v2 (or later)
 * @since     15.01
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Recent Posts sidebar widget.
 *
 * @since 15.01
 */
class Thisismyurl_Easy_Recent_Posts_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'thisismyurl_easy_recent_posts',
			esc_html__( 'Easy Recent Posts', 'easy-recent-posts' ),
			array(
				'classname'   => 'widget_thisismyurl_recent_posts',
				'description' => esc_html__( 'Display your most recent posts.', 'easy-recent-posts' ),
			)
		);
	}

	/**
	 * Save and sanitize widget settings.
	 *
	 * @param array $new_instance New values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                  = array();
		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['post_count']    = absint( $new_instance['post_count'] );
		$instance['include_link']  = (int) ! empty( $new_instance['include_link'] );
		$instance['nofollow']      = (int) ! empty( $new_instance['nofollow'] );
		$instance['show_excerpt']  = (int) ! empty( $new_instance['show_excerpt'] );
		$instance['feature_image'] = (int) ! empty( $new_instance['feature_image'] );
		$instance['show_credit']   = (int) ! empty( $new_instance['show_credit'] );
		$instance['show']          = 0;

		return $instance;
	}

	/**
	 * Render the widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		global $thisismyurl_easy_recent_posts;

		$instance = wp_parse_args( (array) $instance, $thisismyurl_easy_recent_posts->recent_posts_defaults() );

		$count_options = '';
		for ( $i = 5; $i <= 25; $i += 5 ) {
			$count_options .= sprintf(
				'<option value="%d"%s>%d</option>',
				$i,
				selected( $i, (int) $instance['post_count'], false ),
				$i
			);
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'easy-recent-posts' ); ?></label>
			<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<input type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'include_link' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'include_link' ) ); ?>"
				value="1"
				<?php checked( 1, (int) $instance['include_link'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'include_link' ) ); ?>"><?php esc_html_e( 'Include links?', 'easy-recent-posts' ); ?></label>
		</p>

		<p>
			<input type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'nofollow' ) ); ?>"
				value="1"
				<?php checked( 1, (int) $instance['nofollow'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>"><?php esc_html_e( 'Mark links nofollow?', 'easy-recent-posts' ); ?></label>
		</p>

		<p>
			<input type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>"
				value="1"
				<?php checked( 1, (int) $instance['show_excerpt'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Include excerpt?', 'easy-recent-posts' ); ?></label>
		</p>

		<p>
			<input type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'feature_image' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'feature_image' ) ); ?>"
				value="1"
				<?php checked( 1, (int) $instance['feature_image'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'feature_image' ) ); ?>"><?php esc_html_e( 'Include featured image?', 'easy-recent-posts' ); ?></label>
		</p>

		<p>
			<input type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'show_credit' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_credit' ) ); ?>"
				value="1"
				<?php checked( 1, (int) $instance['show_credit'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_credit' ) ); ?>"><?php esc_html_e( 'Include credit link?', 'easy-recent-posts' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'easy-recent-posts' ); ?></label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'post_count' ) ); ?>">
				<?php echo $count_options; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
			</select>
		</p>
		<?php
	}

	/**
	 * Render the widget on the front end.
	 *
	 * @param array $args     Theme-supplied wrapper arguments.
	 * @param array $instance Saved widget settings.
	 */
	public function widget( $args, $instance ) {
		global $thisismyurl_easy_recent_posts;

		$instance    = wp_parse_args( (array) $instance, $thisismyurl_easy_recent_posts->recent_posts_defaults() );
		$recent_posts = $thisismyurl_easy_recent_posts->easy_recent_posts( $instance );

		if ( empty( $recent_posts ) ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied.

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		if ( 1 === (int) $instance['show_credit'] && $title ) {
			$title = sprintf(
				'<a href="%s" title="%s">%s</a>',
				esc_url( 'https://thisismyurl.com/downloads/easy-recent-posts/' ),
				esc_attr__( 'Easy Recent Posts', 'easy-recent-posts' ),
				esc_html( $title )
			);
		} elseif ( $title ) {
			$title = esc_html( $title );
		}

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied wrappers; title is escaped above.
		}

		echo '<ul>' . $recent_posts . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside easy_recent_posts().

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied.
	}
}
