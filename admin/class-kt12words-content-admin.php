<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Words_Content
 * @subpackage Words_Content/admin
 * @author     Karthik Thayyil <me@kt12.in>
 */
class Kt12Words_Content_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Redirect user to setting page on plugin activation
	 *
	 * @since 1.0.0
	 */
	public function plugin_activated_redirect() {
		if ( get_option( 'kt12words_content_activated', false ) ) {
			delete_option( 'kt12words_content_activated' );
			exit(
				esc_url_raw(
					wp_safe_redirect(
						add_query_arg(
							array(
								'page'  => 'kt12words-content-settings',
								'first' => '1',
							),
							admin_url( 'admin.php' )
						)
					)
				)
			);
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param      string $hook     Top Level Page Hook.
	 */
	public function enqueue_styles( $hook ) {
		if ( 'toplevel_page_kt12words-content' === $hook ) {
			wp_enqueue_style( $this->plugin_name . '_bootstrap4', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/css/bootstrap.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_datatble_bootstrap', plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_vue_component_sidebar_style', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/css/custom-style.css', array(), $this->version, 'all' );
			$custom_sidebar_markup_style = get_option( 'kt12word_custom_sidebar_style', '' );
			wp_add_inline_style( $this->plugin_name . '_vue_component_sidebar_style', $custom_sidebar_markup_style );
		}
		wp_enqueue_style( $this->plugin_name . '_admin', plugin_dir_url( __FILE__ ) . 'css/kt12words-content-admin.css', array(), $this->version, 'all' );

		if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();// support for contact form 7 styles.
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param      string $hook     Top Level Page Hook.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_kt12words-content' === $hook ) {
			if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts(); // support for contact form 7 script.
			}
			wp_enqueue_script( $this->plugin_name . '_datable_js', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '_datatble_bootstrap_js', plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '_vue_2_4_4_js', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/vue.js', array(), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '_axios', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/axios.min.js', array(), $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_qs_js', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/qs.js', array(), $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_custom_vue_script_js', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/vue-script.js', array( $this->plugin_name . '_vue_2_4_4_js', $this->plugin_name . '_axios', $this->plugin_name . '_qs_js' ), $this->version, true );

			$footer_content = trim( sanitize_kt12word_content( get_option( 'kt12word_footer_content', '' ) ) );
			wp_localize_script(
				$this->plugin_name . '_custom_vue_script_js',
				'vue_object',
				array(
					'ajax_url'         => admin_url( 'admin-ajax.php' ),
					'security_id'      => wp_create_nonce( 'kt12words-by-id-nonce' ),
					'security_content' => wp_create_nonce( 'kt12words-by-content-nonce' ),
					'footer_content'   => $footer_content,
				)
			);
		}
		wp_enqueue_script( $this->plugin_name . '_admin', plugin_dir_url( __FILE__ ) . 'js/kt12words-content-admin.js', array( 'jquery', $this->plugin_name . '_datable_js' ), $this->version, true );
	}

	/**
	 * Create a menu for the plugin page and pass a callback to it
	 *
	 * @return void
	 */
	public function plugin_page_menu() {
		add_menu_page(
			__( "Word's Content", 'kt12words-content' ),
			__( "Word's Content", 'kt12words-content' ),
			'manage_options',
			'kt12words-content',
			array( $this, 'routes' ),
			'dashicons-editor-paste-word',
			30
		);
	}

	/**
	 * Handles routing of admin page url for this plugin
	 *
	 * @return void
	 */
	public function routes() {
		$view = filter_input( INPUT_GET, 'view', FILTER_DEFAULT );
		switch ( $view ) {
			case 'add-new':
				$this->add_word_page();
				break;
			case 'edit':
				$edit_nonce = filter_input( INPUT_GET, '__edit_nonce', FILTER_DEFAULT );
				if ( ! empty( $edit_nonce ) && wp_verify_nonce( $edit_nonce, 'edit_kt12word_nonce' ) ) {
					$word_id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
					$this->edit_word_page( $word_id );
				} else {
					exit;
				}
				break;
			case 'list':
			default:
				$this->list_words_page();
		}
	}

	/**
	 * Callback functions for list page, shows the words in a table
	 *
	 * @return void
	 */
	public function list_words_page() {
		global $wpdb;

		$results      = $wpdb->get_results( "select * from {$wpdb->prefix}kt12words_content" );
		$add_new_page = add_query_arg(
			array(
				'page' => 'kt12words-content',
				'view' => 'add-new',
			),
			admin_url( 'admin.php' )
		);
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/kt12words-content-admin-main-page-display.php';
		echo ob_get_clean();
	}

	/**
	 * Callback function for create word page, shows the create word form
	 *
	 * @return void
	 */
	public function add_word_page() {
		$word_id     = '';
		$word        = '';
		$word_title  = '';
		$content     = '';
		$form_action = admin_url( 'admin-post.php' );
		$action      = 'kt12word-add-new-word';
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/kt12words-content-admin-form-page-display.php';
		echo ob_get_clean();
	}

	/**
	 * Callback fro edit word page, shows edit form
	 *
	 * @param integer $word_id  database id of the word.
	 * @return void
	 */
	public function edit_word_page( $word_id ) {
		global $wpdb;

		$retrived_word = $wpdb->get_row( $wpdb->prepare( "select * from {$wpdb->prefix}kt12words_content where id=%d", $word_id ) );
		$word          = $retrived_word->word;
		$word_title    = $retrived_word->title;
		$content       = $retrived_word->content;
		$form_action   = admin_url( 'admin-post.php' );
		$action        = 'kt12word-update-word';
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/kt12words-content-admin-form-page-display.php';
		echo ob_get_clean();
	}

	/**
	 * Handles request from add form. Inserts a new word
	 *
	 * @return void
	 */
	public function create_word() {
		global $wpdb;
		$word    = strtolower( wp_strip_all_tags( filter_input( INPUT_POST, 'kt12word_word', FILTER_DEFAULT ), true ) );
		$title   = wp_strip_all_tags( filter_input( INPUT_POST, 'kt12word_title', FILTER_DEFAULT ), true );
		$content = trim( wp_kses_post( filter_input( INPUT_POST, 'kt12word_content', FILTER_DEFAULT ) ) );
		if ( empty( $word ) || empty( $title ) || empty( $content ) ) {
			set_kt12word_content_msg( 'Fields cannot be left blank.', 'error' );
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'kt12words-content',
						'view' => 'add-new',
					),
					admin_url( 'admin.php' )
				)
			);
			exit;
		}

		// check for uniquness // words are case insensitive.
		$word_id      = $wpdb->get_var( $wpdb->prepare( "select id from {$wpdb->prefix}kt12words_content where word=%s", $word ) );
		$current_user = wp_get_current_user();

		if ( is_null( $word_id ) ) {
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'kt12words_content',
				array(
					'word'       => $word,
					'title'      => $title,
					'content'    => $content,
					'created_by' => $current_user->ID,
					'created_at' => current_time( 'timestamp' ),
					'updated_by' => $current_user->ID,
					'updated_at' => current_time( 'timestamp' ),
				),
				array( '%s', '%s', '%s', '%d', '%d', '%d', '%d' )
			);

			if ( $inserted ) {
				$edit_page_url = add_query_arg(
					array(
						'page'         => 'kt12words-content',
						'view'         => 'edit',
						'id'           => $wpdb->insert_id,
						'__edit_nonce' => wp_create_nonce( 'edit_kt12word_nonce' ),
					),
					admin_url( 'admin.php' )
				);
				set_kt12word_content_msg( __( 'Word added sucessfully.', 'kt12words-content' ), 'success' );
				wp_safe_redirect( $edit_page_url );
			} else {
				set_kt12word_content_msg( __( 'Word couldn\'t be added sucessfully. Please try again.', 'kt12words-content' ), 'error' );
				wp_safe_redirect(
					add_query_arg(
						array(
							'page' => 'kt12words-content',
							'view' => 'add-new',
						),
						admin_url( 'admin.php' )
					)
				);
			}
			exit;
		} else {
			set_kt12word_content_msg( __( 'Word already present. Please check the list or add a new one.', 'kt12words-content' ), 'error' );
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'kt12words-content',
						'view' => 'add-new',
					),
					admin_url( 'admin.php' )
				)
			);
		}
	}


	/**
	 * Update the word with the new content
	 *
	 * @return void
	 */
	public function update_word() {
		global $wpdb;

		$word_id = filter_input( INPUT_POST, 'kt12word_word_id', FILTER_VALIDATE_INT );

		$word          = strtolower( wp_strip_all_tags( filter_input( INPUT_POST, 'kt12word_word', FILTER_DEFAULT ), true ) );
		$title         = wp_strip_all_tags( filter_input( INPUT_POST, 'kt12word_title', FILTER_DEFAULT ), true );
		$content       = trim( wp_kses_post( filter_input( INPUT_POST, 'kt12word_content', FILTER_DEFAULT ) ) );
		$edit_page_url = add_query_arg(
			array(
				'page'         => 'kt12words-content',
				'view'         => 'edit',
				'id'           => $word_id,
				'__edit_nonce' => wp_create_nonce( 'edit_kt12word_nonce' ),
			),
			admin_url( 'admin.php' )
		);

		$is_word_present = $wpdb->get_var( $wpdb->prepare( "select id from {$wpdb->prefix}kt12words_content where id = %d", $word_id ) );
		if ( is_null( $is_word_present ) ) {
			set_kt12word_content_msg( __( 'Invalid access. Record for the word id not found.', 'kt12words-content' ), 'error' );
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'kt12words-content',
						'view' => 'add-new',
					),
					admin_url( 'admin.php' )
				)
			);
			exit;
		}

		if ( empty( $word ) || empty( $title ) || empty( $content ) ) {
			set_kt12word_content_msg( 'Fields cannot be left blank.', 'error' );

			wp_safe_redirect( $edit_page_url );
			exit;

		}

		$current_user = wp_get_current_user();

		// check for uniquness // words are case insensitive.
		$preset_word_id = $wpdb->get_var( $wpdb->prepare( "select id from {$wpdb->prefix}kt12words_content where word=%s and id != %d", $word, $word_id ) );

		if ( is_null( $preset_word_id ) ) {
			$updated = $wpdb->update(
				$wpdb->prefix . 'kt12words_content',
				array(
					'word'       => $word,
					'title'      => $title,
					'content'    => $content,
					'updated_by' => $current_user->ID,
					'updated_at' => current_time( 'timestamp' ),
				),
				array( 'id' => $word_id ),
				array( '%s', '%s', '%s', '%d', '%d' ),
				array( '%d' )
			);

			if ( false === $updated ) {
				set_kt12word_content_msg( __( 'Word update failed.', 'kt12words-content' ), 'error' );
			} elseif ( 0 === $updated ) {
				set_kt12word_content_msg( __( 'No changes to update.', 'kt12words-content' ), 'error' );
			} else {
				set_kt12word_content_msg( __( 'Word updated sucessfully.', 'kt12words-content' ), 'success' );
			}
			wp_safe_redirect( $edit_page_url );
			exit;
		} else {
			set_kt12word_content_msg( __( 'The update word seems to be already present in the system with a different id. Please check the list for the word.', 'kt12words-content' ), 'error' );
			wp_safe_redirect( $edit_page_url );
		}
	}

	/**
	 * Deletes the word from database
	 *
	 * @return void
	 */
	public function delete_word() {
		global $wpdb;
		if ( ! empty( filter_input( INPUT_POST, '__delete_nonce', FILTER_DEFAULT ) ) && check_admin_referer( 'delete_kt12word_nonce', '__delete_nonce' ) ) {

			// process form data.
			$word_id = filter_input( INPUT_POST, 'kt12word_word_id', FILTER_VALIDATE_INT );
			$deleted = $wpdb->delete( $wpdb->prefix . 'kt12words_content', array( 'id' => $word_id ) );
			if ( $deleted ) {
				set_kt12word_content_msg( __( 'Word deleted successfully.', 'kt12words-content' ), 'success' );
			} else {
				set_kt12word_content_msg( __( 'Word deleting failed. Please try again.', 'kt12words-content' ), 'error' );
			}

			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'kt12words-content',
						'view' => 'list',
					),
					admin_url( 'admin.php' )
				)
			);
		}
		exit;
	}

	/**
	 * Notices to be displayed on the current screen.
	 *
	 * @return void
	 */
	public function message_notices() {
		$message_array = get_kt12word_content_msg();
		if ( ! empty( $message_array ) ) {
			if ( 'success' === $message_array['msg_type'] ) {
				$class = 'notice notice-success is-dismissible';
			} elseif ( 'error' === $message_array['msg_type'] ) {
				$class = 'notice notice-error is-dismissible';
			} else {
				$class = 'notice is-dismissible';
			}
			$message = $message_array['message'];
			printf( '<div class="%1$s" id="message"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
		}
	}

	/**
	 *  Remove other plugins notices on WordContent pages
	 *
	 * @since 1.0.0
	 */
	public function other_admin_notices_removal() {
		$page = filter_input( INPUT_GET, 'page', FILTER_DEFAULT );
		if ( in_array( $page, array( 'kt12words-content', 'kt12words-content-settings' ) ) ) {
			remove_all_actions( 'admin_notices', false );
		}
	}
}
