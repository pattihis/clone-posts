<?php
/**
 * Clone Posts
 *
 * @package           Clone_Posts
 * @author            George Pattihis
 * @copyright         2021 George Pattihis
 * @license           GPL-2.0-or-later
 * @link              https://profiles.wordpress.org/pattihis/
 * @since             2.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Clone Posts
 * Plugin URI:        http://wordpress.org/extend/plugins/clone-posts/
 * Description:       Easily clone (duplicate) Posts, Pages and Custom Post Types, including their custom fields (post_meta).
 * Version:           2.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            George Pattihis
 * Author URI:        https://profiles.wordpress.org/pattihis/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clone-posts
 * Domain Path:       /languages
 *
 */

 /*  Copyright 2014  Lukasz Kostrzewa  (email : lukasz.webmaster@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*  Copyright 2021  George Pattihis (gpattihis@gmail.com)

	"Clone Posts" is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	"Clone Posts" is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	"along with Clone Posts". If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'CLONE_POSTS_VERSION', '2.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clone-posts-activator.php
 */
function activate_clone_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clone-posts-activator.php';
	Clone_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clone-posts-deactivator.php
 */
function deactivate_clone_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clone-posts-deactivator.php';
	Clone_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clone_posts' );
register_deactivation_hook( __FILE__, 'deactivate_clone_posts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-clone-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_clone_posts() {

	$plugin = new Clone_Posts();
	$plugin->run();

}
run_clone_posts();
