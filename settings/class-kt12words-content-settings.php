<?php
/**
 * Handles setting page functionalities for this plugin.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/settings
 */

/**
 * Handles setting page functionalities for this plugin.
 *
 * @package    Words_Content
 * @subpackage Words_Content/settings
 * @author     Karthik Thayyil <me@kt12.in>
 */
class Kt12Words_Content_Settings {
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 * @since    1.0.0
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
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook    Toplevel page name.
	 */
	public function enqueue_styles( $hook ) {
		if ( 'words-content_page_kt12words-content-settings' === $hook ) {
			wp_enqueue_style( $this->plugin_name . '_bootstrap4', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/css/bootstrap.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_settings', plugin_dir_url( __FILE__ ) . 'css/kt12words-content-settings.css', array( $this->plugin_name . '_bootstrap4' ), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_select_2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_font_awesome_4', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the settings area.
	 *
	 * @since    1.0.0
	 * @param    string $hook    Toplevel page name.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'words-content_page_kt12words-content-settings' === $hook ) {
			wp_enqueue_script( $this->plugin_name . '_select_2_js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '_settings', plugin_dir_url( __FILE__ ) . 'js/kt12words-content-settings.js', array( 'jquery', $this->plugin_name . '_select_2_js' ), $this->version, false );
		}
	}

	/**
	 * Setting menu for the plugin.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function settings_page_menu() {
		add_submenu_page(
			'kt12words-content',
			__( "Word's Content Plugin Settings", 'kt12words-content' ),
			__( 'Settings', 'kt12words-content' ),
			'manage_options',
			'kt12words-content-settings/',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Settings page for the plugin
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function settings_page() {
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/kt12words-content-settings-display.php';
		echo ob_get_clean();
	}

	/**
	 * Register Setting options.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function display_options() {
		// section name, display name, callback to print description of section, page to which section is attached.
		add_settings_section( 'regex-word-highlight-settings', __( 'General Settings', 'kt12words-content' ), array( $this, 'display_header_options_content' ), 'kt12words-content-options' );

		// setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
		// last field section is optional.
		add_settings_field( 'kt12word_highlight_count', __( 'Maximum highlights per word per content', 'kt12words-content' ), array( $this, 'diaplay_highlight_count_element' ), 'kt12words-content-options', 'regex-word-highlight-settings' );
		add_settings_field( 'kt12word_allowed_post_types', __( 'Allowed Post Types', 'kt12words-content' ), array( $this, 'display_allowed_post_type_element' ), 'kt12words-content-options', 'regex-word-highlight-settings' );
		add_settings_field( 'kt12word_footer_content', __( 'Footer Content', 'kt12words-content' ), array( $this, 'display_footer_content' ), 'kt12words-content-options', 'regex-word-highlight-settings', array( 'id' => 'kt12word_footer_content' ) );
		add_settings_field( 'kt12word_custom_sidebar_style', __( 'Custom Sidebar Style', 'kt12words-content' ), array( $this, 'display_custom_sidebar_style' ), 'kt12words-content-options', 'regex-word-highlight-settings' );

		// section name, form element name, callback for sanitization.
		register_setting( 'regex-word-highlight-settings', 'kt12word_highlight_count' );
		register_setting( 'regex-word-highlight-settings', 'kt12word_allowed_post_types' );
		register_setting( 'regex-word-highlight-settings', 'kt12word_footer_content' );
		register_setting( 'regex-word-highlight-settings', 'kt12word_custom_sidebar_style' );
	}

	/**
	 * Display Option Header
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function display_header_options_content() {
		esc_html_e( "General setting for Word's Content plugin", 'kt12words-content' );
	}

	/**
	 * Display Hilight Count Input Field
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function diaplay_highlight_count_element() {
		?>
			<input type="number" class="width-288" name="kt12word_highlight_count" id="kt12word_highlight_count" value="<?php echo esc_attr( get_option( 'kt12word_highlight_count', '-1' ) ); ?>"
			min="-1" step="1" />
			<small id="kt12word-word" class="form-text text-primary width-288">
				<?php esc_html_e( 'A value of -1 highlights all occurrence of the word in the content.', 'kt12words-content' ); ?>
			</small>
		<?php
	}

	/**
	 * Display Allowed Post Type Multi Drop Down
	 *
	 * @since    1.0.0
	 * @since    1.0.1 $saved_post_types explicitly declared array if empty
	 * @return void
	 */
	public function display_allowed_post_type_element() {
		$args                 = array( 'public' => true );
		$output               = 'objects'; // names or objects, note names is the default.
		$operator             = 'and'; // 'and' or 'or'.
		$available_post_types = get_post_types( $args, $output, $operator );
		$saved_post_types     = get_option( 'kt12word_allowed_post_types' );
		if ( empty($saved_post_types) ) {
			$saved_post_types = array();
		}
		$select_element       = "<select name='kt12word_allowed_post_types[]' multiple='multiple' id='kt12word_allowed_post_types'>";
		foreach ( $available_post_types as $post_type ) {
			$select_element .= "<option value='{$post_type->name}' " . ( in_array( $post_type->name, $saved_post_types, true ) ? "selected='selected'" : '' ) . ">{$post_type->label}</option>";
		}
		$select_element .= '</select>';
		echo $select_element;
		?>
			<small id="kt12word-word" class="form-text text-primary width-288">
			<?php esc_html_e( 'If left blank all post type will be taken into consideration.', 'kt12words-content' ); ?>
			</small>
		<?php
	}

	/**
	 * Display field for sidebar footer area
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function display_footer_content() {
		?>
			<textarea cols="30" rows="4" name="kt12word_footer_content"><?php echo sanitize_kt12word_content( get_option( 'kt12word_footer_content', '' ) ); ?></textarea>
			<small id="kt12word-word" class="form-text text-primary width-288">
			<?php esc_html_e( 'The content entered here will be shown in the sidebar footer area.', 'kt12words-content' ); ?>
			</small>
			<?php if ( isset( $_GET['first'] ) && $_GET['first'] == 1 ) : ?>
			<div class="card card-inverse card-danger mb-3 text-center width-288 pulse">
				<div class="card-block" >
				<blockquote class="card-blockquote">
					<p> <?php esc_html_e( 'Please Make Change to the Footer Content if you don\'t want to see \'Powered By Word\'s Content\' in the sliding sidebar.', 'kt12words-content' ); ?></p>
				</blockquote>
				</div>
			</div>
		<?php
		endif;
	}

	/**
	 * Display field for custom sidebar CSS
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function display_custom_sidebar_style() {
		?>
			<textarea cols="30" rows="10" name="kt12word_custom_sidebar_style"><?php echo esc_html( get_option( 'kt12word_custom_sidebar_style', '' ) ); ?></textarea>
			<small id="kt12word-word" class="form-text text-primary width-288">
				<?php esc_html_e( 'Enter your custom css here.', 'kt12words-content' ); ?>
			</small>
		<?php
	}

	/**
	 * Settings data sanitization before storing
	 *
	 * @since    1.0.0
	 * @param mixed  $value value to be stored.
	 * @param string $option option name.
	 * @param  mixed  $old_value current value in database before update.
	 * @return mixed $value value to be updated.
	 */
	public function filter_option_values( $value, $option, $old_value ) {
		switch ( $option ) {
			case 'kt12word_custom_sidebar_style':
				$value = wp_strip_all_tags( $value );
				break;
			case 'kt12word_footer_content':
				$value = sanitize_kt12word_content( $value );
				break;
		}
		return $value;
	}
}
