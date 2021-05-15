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

<div class="wrap">
    <h1>Clone Posts Settings</h1>
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
