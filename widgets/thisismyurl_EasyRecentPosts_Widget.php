<?php
/**
 * Easy Recent Posts Widget.
 *
 * @package easy-recent-posts
 * @author  Christopher Ross
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Widget class for Easy Recent Posts.
 */
class thisismyurl_EasyRecentPosts_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'widget_thisismyurl_recent_posts',
			'description' => __( 'A WordPress widget to add recent posts to any WordPress theme.', 'easy-recent-posts' ),
		);
		$control_ops = array(
			'width'  => 300,
			'height' => 300,
			'id'     => 'widget_thisismyurl_recent_posts',
		);
		parent::__construct(
			'thisismyurl_EasyRecentPosts_Widget',
			__( 'Easy Recent Posts', 'easy-recent-posts' ),
			$widget_ops,
			$control_ops
		);
	}

	/**
	 * Sanitize and save widget settings.
	 *
	 * @param array $new_instance New settings submitted by the user.
	 * @param array $old_instance Previous saved settings (unused).
	 * @return array Sanitized instance ready for storage.
	 */
	public function update( $new_instance, $old_instance ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$instance                  = array();
		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['post_count']    = absint( $new_instance['post_count'] );
		$instance['orderby']       = in_array(
			$new_instance['orderby'],
			array( 'date', 'title', 'rand', 'comment_count', 'modified' ),
			true
		)
			? $new_instance['orderby']
			: 'date';
		$instance['order']         = 'ASC' === strtoupper( $new_instance['order'] ) ? 'ASC' : 'DESC';
		$instance['include_link']  = (int) isset( $new_instance['include_link'] );
		$instance['nofollow']      = (int) isset( $new_instance['nofollow'] );
		$instance['show_excerpt']  = (int) isset( $new_instance['show_excerpt'] );
		$instance['feature_image'] = (int) isset( $new_instance['feature_image'] );
		$instance['show_credit']   = (int) isset( $new_instance['show_credit'] );
		$instance['show']          = 0;
		return $instance;
	}

	/**
	 * Render the widget settings form in the admin.
	 *
	 * @param array $instance Current saved settings.
	 * @return void
	 */
	public function form( $instance ) {
		global $thisismyurl_EasyRecentPosts;
		$instance = wp_parse_args( (array) $instance, $thisismyurl_EasyRecentPosts->recent_posts_defaults() );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'easy-recent-posts' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $instance['title'] ); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>">
				<?php esc_html_e( 'Number of posts to show:', 'easy-recent-posts' ); ?>
			</label>
			<input
				id="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'post_count' ) ); ?>"
				type="number"
				min="1"
				value="<?php echo absint( $instance['post_count'] ); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
				<?php esc_html_e( 'Order by:', 'easy-recent-posts' ); ?>
			</label>
			<select
				id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>"
			>
				<option value="date" <?php selected( $instance['orderby'], 'date' ); ?>>
					<?php esc_html_e( 'Date', 'easy-recent-posts' ); ?>
				</option>
				<option value="title" <?php selected( $instance['orderby'], 'title' ); ?>>
					<?php esc_html_e( 'Title', 'easy-recent-posts' ); ?>
				</option>
				<option value="rand" <?php selected( $instance['orderby'], 'rand' ); ?>>
					<?php esc_html_e( 'Random', 'easy-recent-posts' ); ?>
				</option>
				<option value="comment_count" <?php selected( $instance['orderby'], 'comment_count' ); ?>>
					<?php esc_html_e( 'Comment count', 'easy-recent-posts' ); ?>
				</option>
				<option value="modified" <?php selected( $instance['orderby'], 'modified' ); ?>>
					<?php esc_html_e( 'Last modified', 'easy-recent-posts' ); ?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
				<?php esc_html_e( 'Direction:', 'easy-recent-posts' ); ?>
			</label>
			<select
				id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>"
			>
				<option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>>
					<?php esc_html_e( 'Descending', 'easy-recent-posts' ); ?>
				</option>
				<option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>>
					<?php esc_html_e( 'Ascending', 'easy-recent-posts' ); ?>
				</option>
			</select>
		</p>
		<p>
			<input
				id="<?php echo esc_attr( $this->get_field_id( 'include_link' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'include_link' ) ); ?>"
				type="checkbox"
				value="1"
				<?php checked( $instance['include_link'], 1 ); ?>
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'include_link' ) ); ?>">
				<?php esc_html_e( 'Include link', 'easy-recent-posts' ); ?>
			</label>
		</p>
		<p>
			<input
				id="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'nofollow' ) ); ?>"
				type="checkbox"
				value="1"
				<?php checked( $instance['nofollow'], 1 ); ?>
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>">
				<?php esc_html_e( 'Nofollow link', 'easy-recent-posts' ); ?>
			</label>
		</p>
		<p>
			<input
				id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>"
				type="checkbox"
				value="1"
				<?php checked( $instance['show_excerpt'], 1 ); ?>
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>">
				<?php esc_html_e( 'Show excerpt', 'easy-recent-posts' ); ?>
			</label>
		</p>
		<p>
			<input
				id="<?php echo esc_attr( $this->get_field_id( 'feature_image' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'feature_image' ) ); ?>"
				type="checkbox"
				value="1"
				<?php checked( $instance['feature_image'], 1 ); ?>
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'feature_image' ) ); ?>">
				<?php esc_html_e( 'Show featured image', 'easy-recent-posts' ); ?>
			</label>
		</p>
		<p>
			<input
				id="<?php echo esc_attr( $this->get_field_id( 'show_credit' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_credit' ) ); ?>"
				type="checkbox"
				value="1"
				<?php checked( $instance['show_credit'], 1 ); ?>
			/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_credit' ) ); ?>">
				<?php esc_html_e( 'Show credit link', 'easy-recent-posts' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Render the widget on the front end.
	 *
	 * @param array $args     Theme-provided widget wrapper markup (before_widget, after_widget, etc.).
	 * @param array $instance Saved widget settings.
	 */
	public function widget( $args, $instance ) {
		global $thisismyurl_EasyRecentPosts;
		$instance     = wp_parse_args( (array) $instance, $thisismyurl_EasyRecentPosts->recent_posts_defaults() );
		$recent_posts = $thisismyurl_EasyRecentPosts->easy_recent_posts( $instance );

		if ( ! empty( $recent_posts ) ) {
			// $args values originate from register_sidebar() in the active theme and are
			// intentionally unescaped per WordPress widget API convention.
			echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			$title = sanitize_text_field( $instance['title'] );
			if ( 1 === (int) $instance['show_credit'] ) {
				$title = sprintf(
					'<a href="%s" title="%s">%s</a>',
					esc_url( 'https://thisismyurl.com/downloads/easy-recent-posts/' ),
					esc_attr__( 'Easy Recent Posts WordPress plugin', 'easy-recent-posts' ),
					esc_html( $title )
				);
			} else {
				$title = esc_html( $title );
			}

			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '<ul>' . $recent_posts . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
