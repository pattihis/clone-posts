<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used in the admin area.
 *
 * @link       https://profiles.wordpress.org/pattihis/
 * @since      2.0.0
 *
 * @package    Clone_Posts
 * @subpackage Clone_Posts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization and admin-specific hooks
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Clone_Posts
 * @subpackage Clone_Posts/includes
 * @author     George Pattihis <gpattihis@gmail.com>
 */
class Clone_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Clone_Posts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale and set the hooks for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		if ( defined( 'CLONE_POSTS_VERSION' ) ) {
			$this->version = CLONE_POSTS_VERSION;
		} else {
			$this->version = '2.1.0';
		}
		$this->plugin_name = 'clone-posts';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Clone_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - Clone_Posts_I18n. Defines internationalization functionality.
	 * - Clone_Posts_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-clone-posts-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-clone-posts-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-clone-posts-admin.php';

		$this->loader = new Clone_Posts_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Clone_Posts_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Clone_Posts_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Clone_Posts_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'clone_posts_register_setting' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'clone_posts_admin_page' );
		$this->loader->add_action( 'admin_footer-edit.php', $plugin_admin, 'clone_posts_admin_footer' );
		$this->loader->add_action( 'load-edit.php', $plugin_admin, 'clone_posts_bulk_action' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'clone_posts_admin_notices' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'clone_posts_post_row_actions', 10, 2 );
		$this->loader->add_filter( 'page_row_actions', $plugin_admin, 'clone_posts_post_row_actions', 10, 2 );
		$this->loader->add_action( 'wp_loaded', $plugin_admin, 'clone_posts_wp_loaded' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    Clone_Posts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
