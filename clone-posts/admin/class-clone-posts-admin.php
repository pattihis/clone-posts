<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/pattihis/
 * @since      2.0.0
 *
 * @package    Clone_Posts
 * @subpackage Clone_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name and version
 *
 * @package    Clone_Posts
 * @subpackage Clone_Posts/admin
 * @author     George Pattichis <gpattihis@gmail.com>
 */
class Clone_Posts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/clone-posts-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the admin settings page
	 *
	 * @since    2.0.0
	 */
	public function clone_posts_admin_page() {

		add_options_page(
			esc_html__( 'Clone Posts Settings', 'clone-posts' ),
			esc_html__( 'Clone Posts', 'clone-posts' ),
			'manage_options',
			'clone-posts-options',
			array( $this, 'clone_posts_admin_display' ),
			null
		);
	}

	/**
	 * Render the settings page content
	 *
	 * @since  2.0.0
	 */
	public function clone_posts_admin_display() {
		include_once 'partials/clone-posts-admin-display.php';
	}

	/**
	 * Register the actual settings
	 *
	 * @since  2.0.0
	 */
	public function clone_posts_register_setting() {

		add_settings_section(
			'clone_posts_settings_section',
			'',
			'',
			'clone-posts-options'
		);

		register_setting(
			'clone_post_settings',
			'clone_posts_post_status',
			'sanitize_text_field'
		);

		add_settings_field(
			'clone_posts_post_status',
			'Post Status',
			array( $this, 'clone_posts_option_post_status' ),
			'clone-posts-options',
			'clone_posts_settings_section',
			array(
				'label_for' => 'clone_posts_post_status',
				'class'     => 'clone-posts',
			)
		);

		register_setting(
			'clone_post_settings',
			'clone_posts_post_date',
			'sanitize_text_field'
		);

		add_settings_field(
			'clone_posts_post_date',
			'Post Date',
			array( $this, 'clone_posts_option_post_date' ),
			'clone-posts-options',
			'clone_posts_settings_section',
			array(
				'label_for' => 'clone_posts_post_date',
				'class'     => 'clone-posts',
			)
		);

		register_setting(
			'clone_post_settings',
			'clone_posts_post_type',
			array( $this, 'clone_posts_sanitize_array' )
		);

		add_settings_field(
			'clone_posts_post_type',
			'Post Type',
			array( $this, 'clone_posts_option_post_type' ),
			'clone-posts-options',
			'clone_posts_settings_section',
			array(
				'label_for' => 'clone_posts_post_type',
				'class'     => 'clone-posts',
			)
		);
	}

	/**
	 * Field for Post Status option
	 *
	 * @since  2.0.0
	 */
	public function clone_posts_option_post_status() {
		$option     = get_option( 'clone_posts_post_status' );
		$status_txt = esc_html__( 'status', 'clone-posts' );
		$link       = '<a href="https://wordpress.org/support/article/post-status/#default-statuses" target="_blank">' . $status_txt . '</a>';
		?>
		<select name="clone_posts_post_status" id="clone_posts_post_status">
			<option value="draft" <?php selected( $option, 'draft' ); ?>>Draft</option>
			<option value="publish" <?php selected( $option, 'publish' ); ?>>Publish</option>
			<option value="private" <?php selected( $option, 'private' ); ?>>Private</option>
			<option value="pending" <?php selected( $option, 'pending' ); ?>>Pending</option>
		</select>
		<?php
		// translators: %s is the link to the WordPress Codex about statuses.
		echo wp_kses_post( '<p>' . sprintf( __( 'Select the %s of the cloned post.', 'clone-posts' ), $link ) . '</p>' );
	}

	/**
	 * Field for Post Date option
	 *
	 * @since  2.0.0
	 */
	public function clone_posts_option_post_date() {
		$option = get_option( 'clone_posts_post_date' );
		?>
		<select name="clone_posts_post_date" id="clone_posts_post_date">
			<option value="current" <?php selected( $option, 'current' ); ?>>Current Date/Time</option>
			<option value="original" <?php selected( $option, 'original' ); ?>>Original Post Date</option>
		</select>
		<p><?php esc_html_e( 'Select the date/time of the cloned post.', 'clone-posts' ); ?></p>
		<?php
	}

	/**
	 * Field for Post Type option
	 *
	 * @since  2.0.0
	 */
	public function clone_posts_option_post_type() {
		$options = maybe_unserialize( get_option( 'clone_posts_post_type' ) );
		if ( ! is_array( $options ) ) {
			$options = array( 'post', 'page' );
		}
		$exclude_cpt = array( 'attachment' );
		$post_types  = get_post_types( array( 'public' => true ), 'objects', 'and' );
		echo '<fieldset>';
		if ( $post_types ) {
			foreach ( $post_types as $post_type ) {
				if ( ! in_array( $post_type->name, $exclude_cpt, true ) ) {
					?>
					<div>
						<input type="checkbox" name="clone_posts_post_type[]" value="<?php echo esc_attr( $post_type->name ); ?>" id="post_type_<?php echo esc_attr( $post_type->name ); ?>" <?php checked( in_array( $post_type->name, $options, true ), 1 ); ?>>
						<label for="post_type_<?php echo esc_attr( $post_type->name ); ?>"><?php echo esc_html( $post_type->labels->name ); ?></label>
					</div>
					<?php
				}
			}
		}
		echo '<p>' . esc_html__( 'Enable Clone for the above Post Types', 'clone-posts' ) . '</p></fieldset>';
	}

	/**
	 * A custom sanitization function for arrays.
	 *
	 * @since    2.0.0
	 * @param    array $input        The posted array.
	 * @return   array    $output       The array sanitized.
	 */
	public function clone_posts_sanitize_array( $input ) {
		$output = array();
		foreach ( $input as $key => $val ) {
			$output[ $key ] = ( isset( $input[ $key ] ) ) ? sanitize_text_field( $val ) : '';
		}
		return $output;
	}

	/**
	 * Add the custom Bulk Action to the select menus
	 *
	 * @since    2.0.0
	 */
	public function clone_posts_admin_footer() {
		$options = maybe_unserialize( get_option( 'clone_posts_post_type' ) );

		if ( ! is_array( $options ) ) {
			$options = array( 'post', 'page' );
		}

		if ( ! in_array( $GLOBALS['post_type'], $options, true ) ) {
			return;
		}
		?>
		<script type="text/javascript">
			jQuery(function() {
				jQuery('<option>').val('clone').text('<?php esc_html_e( 'Clone' ); ?>').appendTo("select[name='action']");
				jQuery('<option>').val('clone').text('<?php esc_html_e( 'Clone' ); ?>').appendTo("select[name='action2']");
			});
		</script>
		<?php
	}

	/**
	 * Handle the custom Bulk Action
	 *
	 * @since    2.0.0
	 */
	public function clone_posts_bulk_action() {
		global $typenow;
		$post_type = $typenow;

		// get the action.
		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$action        = $wp_list_table->current_action();

		$allowed_actions = array( 'clone' );
		if ( ! in_array( $action, $allowed_actions, true ) ) {
			return;
		}

		// security check.
		check_admin_referer( 'bulk-posts' );

		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'.
		if ( isset( $_REQUEST['post'] ) ) {
			$post_ids = array_map( 'intval', $_REQUEST['post'] );
		}

		if ( empty( $post_ids ) ) {
			return;
		}

		// this is based on wp-admin/edit.php .
		$sendback = remove_query_arg( array( 'cloned', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
		if ( ! $sendback ) {
			$sendback = admin_url( "edit.php?post_type=$post_type" );
		}

		$pagenum  = $wp_list_table->get_pagenum();
		$sendback = add_query_arg( 'paged', $pagenum, $sendback );

		switch ( $action ) {
			case 'clone':
				$cloned = 0;
				foreach ( $post_ids as $post_id ) {

					if ( ! current_user_can( 'edit_post', $post_id ) ) {
						wp_die( esc_html__( 'You are not allowed to clone this post.', 'clone-posts' ) );
					}

					if ( ! $this->clone_posts_clone_single( $post_id ) ) {
						wp_die( esc_html__( 'Error cloning post.', 'clone-posts' ) );
					}

					++$cloned;
				}

				$sendback = add_query_arg(
					array(
						'cloned' => $cloned,
						'ids'    => join(
							',',
							$post_ids
						),
					),
					$sendback
				);
				break;

			default:
				return;
		}

		$sendback = remove_query_arg(
			array(
				'action',
				'action2',
				'tags_input',
				'post_author',
				'comment_status',
				'ping_status',
				'_status',
				'post',
				'bulk_edit',
				'post_view',
			),
			$sendback
		);

		wp_safe_redirect( $sendback );
		exit();
	}

	/**
	 * Display an admin notice on the Posts page after cloning
	 *
	 * @since    2.0.0
	 */
	public function clone_posts_admin_notices() {
		global $pagenow;

		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( 'edit.php' === $pagenow && ! isset( $_GET['trashed'] ) ) {
			$cloned = 0;
			if ( isset( $_REQUEST['cloned'] ) && (int) $_REQUEST['cloned'] ) {
				$cloned = (int) $_REQUEST['cloned'];
			} elseif ( isset( $_GET['cloned'] ) && (int) $_GET['cloned'] ) {
				$cloned = (int) $_GET['cloned'];
			}
			if ( $cloned ) {
				/* translators: %s is the number of clomned posts. */
				$message = sprintf( _n( '%s Post cloned.', '%s Posts cloned.', $cloned ), number_format_i18n( $cloned ) );
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
			}
		}
	}

	/**
	 * Filters the array of row action links on the admin table.
	 *
	 * @since    2.0.0
	 *
	 * @param array   $actions An array of row action links.
	 * @param WP_Post $post    The post object.
	 */
	public function clone_posts_post_row_actions( $actions, $post ) {
		global $post_type;

		$options = maybe_unserialize( get_option( 'clone_posts_post_type' ) );

		if ( ! is_array( $options ) ) {
			$options = array( 'post', 'page' );
		}

		if ( ! in_array( $post_type, $options, true ) ) {
			return $actions;
		}

		$url = remove_query_arg( array( 'cloned', 'untrashed', 'deleted', 'ids' ), '' );
		if ( ! $url ) {
			$url = admin_url( "?post_type=$post_type" );
		}
		$url = remove_query_arg(
			array(
				'action',
				'action2',
				'tags_input',
				'post_author',
				'comment_status',
				'ping_status',
				'_status',
				'post',
				'bulk_edit',
				'post_view',
			),
			$url
		);
		$url = add_query_arg(
			array(
				'action'   => 'clone-single',
				'post'     => $post->ID,
				'redirect' => isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : get_home_url(),
			),
			$url
		);

		$actions['clone'] = '<a href=\'' . $url . '\'>' . __( 'Clone', 'clone-posts' ) . '</a>';
		return $actions;
	}

	/**
	 * Fires before admin_init, clears query args and redirects
	 *
	 * @since    2.0.0
	 */
	public function clone_posts_wp_loaded() {
		global $post_type;

		if ( ! isset( $_GET['action'] ) || 'clone-single' !== $_GET['action'] || ! isset( $_GET['post'] ) || ! isset( $_GET['redirect'] ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'You are not allowed to clone this post.', 'clone-posts' ) );
		}

		if ( ! $this->clone_posts_clone_single( $post_id ) ) {
			wp_die( esc_html__( 'Error cloning post.', 'clone-posts' ) );
		}

		$sendback = remove_query_arg( array( 'cloned', 'untrashed', 'deleted', 'ids' ), esc_url_raw( wp_unslash( $_GET['redirect'] ) ) );
		if ( ! $sendback ) {
			$sendback = admin_url( "edit.php?post_type=$post_type" );
		}

		$sendback = add_query_arg( array( 'cloned' => 1 ), $sendback );
		$sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view' ), $sendback );

		wp_safe_redirect( $sendback );
		exit();
	}

	/**
	 * Clone the Post
	 *
	 * @param int $id The Post ID.
	 * @since    2.0.0
	 */
	public function clone_posts_clone_single( $id ) {
		$p = get_post( $id );
		if ( null === $p ) {
			return false;
		}

		$newpost = array(
			'post_name'             => $p->post_name,
			'post_type'             => $p->post_type,
			'ping_status'           => $p->ping_status,
			'post_parent'           => $p->post_parent,
			'menu_order'            => $p->menu_order,
			'post_password'         => $p->post_password,
			'post_excerpt'          => $p->post_excerpt,
			'comment_status'        => $p->comment_status,
			'post_title'            => $p->post_title . __( ' - Clone', 'clone-posts' ),
			'post_content'          => $p->post_content,
			'post_author'           => $p->post_author,
			'to_ping'               => $p->to_ping,
			'pinged'                => $p->pinged,
			'post_content_filtered' => $p->post_content_filtered,
			'post_category'         => $p->post_category,
			'tags_input'            => $p->tags_input,
			'tax_input'             => $p->tax_input,
			'page_template'         => $p->page_template,
		);

		$post_status = get_option( 'clone_posts_post_status' );
		if ( 'draft' !== $post_status ) {
			$newpost['post_status'] = $post_status;
		}

		$date = get_option( 'clone_posts_post_date' );
		if ( 'current' !== $date ) {
			$newpost['post_date']     = $p->post_date;
			$newpost['post_date_gmt'] = $p->post_date_gmt;
		}

		$newid  = wp_insert_post( $newpost );
		$format = get_post_format( $id );
		set_post_format( $newid, $format );

		$meta = get_post_meta( $id );
		foreach ( $meta as $key => $val ) {
			update_post_meta( $newid, $key, maybe_unserialize( $val[0] ) );
		}

		return true;
	}
}
