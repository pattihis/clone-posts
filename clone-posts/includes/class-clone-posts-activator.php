<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/pattihis/
 * @since      2.0.0
 *
 * @package    Clone_Posts
 * @subpackage Clone_Posts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Clone_Posts
 * @subpackage Clone_Posts/includes
 * @author     George Pattihis <gpattihis@gmail.com>
 */
class Clone_Posts_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function activate() {

		update_option('clone_posts_post_status', 'draft');
		update_option('clone_posts_post_date', 'current');
		update_option('clone_posts_post_type', ['post']);

	}

}
