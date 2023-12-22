<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://profiles.wordpress.org/pattihis/
 * @since      2.0.0
 *
 * @package    Clone_Posts
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// If we are on a multisite installation clean up all subsites.
if ( is_multisite() ) {

	foreach ( get_sites( array( 'fields' => 'ids' ) ) as $blog_id_number ) {
		switch_to_blog( $blog_id_number );
		clone_posts_cleanup();
		restore_current_blog();
	}
} else {
	clone_posts_cleanup();
}

/**
 * Cleans up after plugin's uninstallation.
 *
 * @since     2.0.0
 * @return    void
 */
function clone_posts_cleanup() {

	// Plugin options.
	$options = array(
		'clone_posts_post_status',
		'clone_posts_post_date',
		'clone_posts_post_type',
	);

	// Loop through each option.
	foreach ( $options as $option ) {
		delete_option( $option );
	}
}
