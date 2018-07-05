<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Words_Content
 * @subpackage Words_Content/includes
 * @author     Karthik Thayyil <me@kt12.in>
 */
class Kt12Words_Content {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Kt12Words_Content_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'kt12words-content';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_hooks();
		$this->define_settings_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 * - Helper function file
	 * - Kt12Words_Content_Loader. Orchestrates the hooks of the plugin.
	 * - Kt12Words_Content_i18n. Defines internationalization functionality.
	 * - Kt12Words_Content_Admin. Defines all hooks for the admin area.
	 * - Kt12Words_Content_Public. Defines all hooks for the public side of the site.
	 * - Kt12Words_Content_Settings. Defines all hooks for the settings page.
	 * - Kt12Words_Content_Ajax. Defines all ajax hooks.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Contains a set of helper function
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper.php';

		/**
		 *  The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kt12words-content-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kt12words-content-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kt12words-content-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kt12words-content-public.php';

		/**
		 * The class responsible for defining all ajax actions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ajax/class-kt12words-content-ajax.php';

		/**
		 * The class responsible for defining all actions that occur in the plugins settings page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'settings/class-kt12words-content-settings.php';

		$this->loader = new Kt12Words_Content_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Kt12Words_Content_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Kt12Words_Content_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Kt12Words_Content_Admin( $this->get_plugin_name(), $this->get_version() );

		// redirect to setting on activation.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'plugin_activated_redirect' );

		// remove other plugins admin notices on words content pages.
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'other_admin_notices_removal', 1 );

		// enqueue styles and scripts.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// register menu.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'plugin_page_menu' );

		// handle page actions.
		$this->loader->add_action( 'admin_post_kt12word-add-new-word', $plugin_admin, 'create_word' );
		$this->loader->add_action( 'admin_post_kt12word-update-word', $plugin_admin, 'update_word' );
		$this->loader->add_action( 'admin_post_kt12word-delete-word', $plugin_admin, 'delete_word' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Kt12Words_Content_Public( $this->get_plugin_name(), $this->get_version() );

		// enqueue styles and scripts.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// filter the content from single post page and mark the words.
		$this->loader->add_filter( 'the_content', $plugin_public, 'mark_words_in_the_content', 100, 1 );
	}

	/**
	 * Register all of the hooks related to the settings page
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_settings_hooks() {
		$plugin_settings = new Kt12Words_Content_Settings( $this->get_plugin_name(), $this->get_version() );

		// enqueue styles and scripts.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_settings, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_settings, 'enqueue_scripts' );

		// register settings sub menu page.
		$this->loader->add_action( 'admin_menu', $plugin_settings, 'settings_page_menu' );

		// display settings options.
		$this->loader->add_action( 'admin_init', $plugin_settings, 'display_options' );

		// filter setting value.
		$this->loader->add_filter( 'pre_update_option', $plugin_settings, 'filter_option_values', 11, 3 );
	}

	/**
	 * Register all of the hooks related to the ajax functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_ajax_hooks() {
		$plugin_ajax = new Kt12Words_Content_Ajax( $this->get_plugin_name(), $this->get_version() );

		// Handle ajax call that gets word content by the word id.
		$this->loader->add_action( 'wp_ajax_word_ajax_content_by_id', $plugin_ajax, 'ajax_to_get_word_content_by_id' );
		$this->loader->add_action( 'wp_ajax_nopriv_word_ajax_content_by_id', $plugin_ajax, 'ajax_to_get_word_content_by_id' );

		// Handle ajax call that process raw data from the editor to its final form for preview.
		if ( is_admin() ) {
			$this->loader->add_action( 'wp_ajax_word_ajax_process_raw_content', $plugin_ajax, 'ajax_to_process_raw_content' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Kt12Words_Content_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
