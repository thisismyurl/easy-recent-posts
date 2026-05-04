<?php
/*
Plugin Name: Easy Recent Posts
Plugin URI: http://thisismyurl.com/downloads/easy-recent-posts/
Description: An easy to use WordPress function to add Recent Posts to any theme.
Author: Christopher Ross
Author URI: http://thisismyurl.com/
Tags: future, upcoming posts, upcoming post, upcoming, draft, Post, recent, preview, plugin, post, posts
Version: 15.01
*/



/**
 *
 * Easy Recent Posts core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/wordpresscom-stats-smiley-remover/
 *
 * @package 	Easy Recent Posts
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Easy Recent Posts 1.0
 *
 *
 */

/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;
	
	
define( 'THISISMYURL_EREP_NAME', 'Easy Recent Posts' );
define( 'THISISMYURL_EREP_SHORTNAME', 'Easy Recent Posts' );

define( 'THISISMYURL_EREP_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_EREP_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_EREP_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_EREP_NAMESPACE', basename( THISISMYURL_EREP_FILENAME, '.php' ) );
define( 'THISISMYURL_EREP_TEXTDOMAIN', str_replace( '-', '_', THISISMYURL_EREP_NAMESPACE ) );

define( 'THISISMYURL_EREP_VERSION', '15.01' );

include_once( 'thisismyurl-common.php' );

/**
 * Creates the class required for Easy Recent Posts
 *
 * @author     Christopher Ross <info@thisismyurl.com>
 * @version    Release: @15.01@
 * @see        wp_enqueue_scripts()
 * @since      Class available since Release 14.11
 *
 */
if( ! class_exists( 'thissimyurl_EasyRecentPosts' ) ) {
class thissimyurl_EasyRecentPosts extends thisismyurl_Common_EREP {
	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/add_shortcode
	  * @since Method available since Release 15.01
	  *
	  */
	public function run() {
		
		add_action( 'widgets_init', array( $this, 'widget_init' ) );
		
		add_shortcode( 'thisismyurl_easy_recent_posts', array( $this, 'easy_recent_posts_shortcode' ) );
		
	}
	
	
	
	/**
	  * easy_recent_posts_shortcode helper function
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */
	 function easy_recent_posts_shortcode() {
	
		$recent_posts = $this->easy_recent_posts();
		
		if ( ! empty( $recent_posts ) )
			echo '<ul class="thisismyurl-easy-recent-posts">' . $recent_posts . '</ul>';
			
	} 
	
	
	
	/**
	  * easy_recent_posts
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */
	function easy_recent_posts( $options = NULL ) {

		$options = wp_parse_args( $this->recent_posts_defaults(), $options );
		
		$args = array( 'numberposts' => $options['post_count'], 'orderby' => $options['order'] );
		$recent_posts = get_posts( $args );
		
		if( $recent_posts ) {
			foreach ( $recent_posts as $recent_post ) {
	
				/* place the post title */
				$recent_item = sprintf( '<span class="title">%s</span>', esc_html( get_the_title( $recent_post->ID ) ) );
				
				
				/* if there's a link, display it */
				if ( $options['include_link'] == 1 ) {
				
					if( $options['nofollow'] == 1 )
						$nofollow = 'nofollow';
					else
						$nofollow = '';
						
					$recent_item = sprintf( '<span class="title-link"><a href="%s" title="%s" %s >%s</a><span>',
											get_permalink( $recent_post->ID ),
											esc_attr( get_the_title( $recent_post->ID ) ),
											$nofollow,
											$recent_item
									);	
					
				}
				
				
				/* feature image, if there is one */
				if ( $options['feature_image'] == 1 && has_post_thumbnail( $recent_post->ID ) ) {
					$recent_item = sprintf( '<div class="thumbnail">%s</div>%s', 
											get_the_post_thumbnail($thepost->ID,'thumbnail'),
											$recent_item
											);
				}
				
				
				/* show the excerpt when it's required */
				if ( $options['show_excerpt'] == 1 && ! empty( $recent_post->post_excerpt ) ) {
					
					$recent_item = sprintf( '%s<div class="excerpt">%s</div>', 
											$recent_item,
											esc_html( $recent_post->post_excerpt )
											);
				}
				
	
				/* wrap the content in the proper tags */
				$recent[] =  $options['before'] . $recent_item . $options['after'];
		
			}
	
		}
		
		if ( ! empty( $recent ) ) {
			if ( $options['show'] == 1 )
				echo implode( '', $recent );
			else
				return implode( '', $recent );
		
		}
	
	}
	
	/**
	  * recent_posts_defaults sets defaults for plugin
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */	 
	function recent_posts_defaults() {
	
		$default_options = array(
									'title'     	=> __( 'Easy Recent Posts', THISISMYURL_EREP_NAME ),
									'post_count'    => 10,
									'order'    		=> 'RAND',
									'include_link' 	=> 1,
									'before'   		=> '<li>',
									'after'    		=> '</li>',
									'nofollow' 		=> 0,
									'show_excerpt' 	=> 0,
									'feature_image' => 0,
									'show_credit' 	=> 1,
									'show'     		=> 0,
									
								);
								
		return $default_options;						
								
	}
	
	
	
	/**
	  * widget_init activates the plugin widgets
	  *
	  * @access public
	  * @static
	  * @uses register_widget
	  * @since Method available since Release 15.01
	  *
	  */
	function widget_init() {
		
		include_once( 'widgets/thissimyurl_EasyRecentPosts_Widget.php' );
		register_widget( 'thissimyurl_EasyRecentPosts_Widget' );
	
	}

	  
	
}
}

global $thissimyurl_EasyRecentPosts;

$thissimyurl_EasyRecentPosts = new thissimyurl_EasyRecentPosts;

$thissimyurl_EasyRecentPosts->run();




/**
  * Allows theme authors to call 
  *
  * @access public
  * @static
  * @uses $thissimyurl_EasyRecentPosts->easy_recent_posts
  * @since Method available since Release 15.01
  *
  * @param  array see $thissimyurl_EasyRecentPosts->recent_posts_defaults() for accepted options
  *
  */
if ( ! function_exists( 'thisismyurl_easy_recent_posts' ) ) {
function thisismyurl_easy_recent_posts( $options = NULL ) {
	
	$thissimyurl_EasyRecentPosts->easy_recent_posts( $options );

}
}