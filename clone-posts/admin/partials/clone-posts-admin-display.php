<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/pattihis/
 * @since      2.0.0
 *
 * @package    Clone_Posts
 * @subpackage Clone_Posts/admin/partials
 */

?>

<div class="clone-posts_header">
	<h1><span class="dashicons dashicons-admin-page clone-posts"></span>&nbsp;<?php esc_html_e( 'Clone Posts Settings', 'clone-posts' ); ?></h1>
</div>
<h4><?php esc_html_e( 'Easily clone (duplicate) Posts, Pages and Custom Post Types.', 'clone-posts' ); ?></h4>
<div class="clone-posts_wrap">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'clone_post_settings' );
		do_settings_sections( 'clone-posts-options' );
		submit_button();
		?>
	</form>
</div>
<div class="wrap">
	<p>If you find this free plugin useful then please <a target="_blank" href="https://wordpress.org/support/plugin/clone-posts/reviews/?rate=5#new-post" title="Rate the plugin">rate the plugin ★★★★★</a> to support us. Thank you!</p>
</div>
