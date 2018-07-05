<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Words_Content
 * @subpackage Words_Content/public
 * @author     Karthik Thayyil <me@kt12.in>
 */
class Kt12Words_Content_Public {
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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$allowed_post_types = get_option( 'kt12word_allowed_post_types', array() ); // empty array means all post_type are allowed.
		$current_post_type  = get_post_type();

		if ( ( empty( $allowed_post_types ) || in_array( $current_post_type, $allowed_post_types, true ) ) && ( is_single() || in_array( $current_post_type, array( 'page', 'attachment' ), true ) ) ) {
			wp_enqueue_style( $this->plugin_name . '_vue_component_sidebar_style', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/css/custom-style.css', array(), $this->version, 'all' );
			$custom_sidebar_markup_style = get_option( 'kt12word_custom_sidebar_style', '' );
			wp_add_inline_style( $this->plugin_name . '_vue_component_sidebar_style', $custom_sidebar_markup_style );
			if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
				wpcf7_enqueue_styles();// support for contact form 7 styles.
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$allowed_post_types = get_option( 'kt12word_allowed_post_types', array() ); // empty array means all post_type are allowed.
		$current_post_type  = get_post_type();

		/**
		 * Enqueue scripts only on single post pages, post type 'attachment' and 'page' do not have single post page so needs to be handle saperatly
		 * if $allowed_post_types  is blank all publicly available post type is considered valid
		 */
		if ( ( empty( $allowed_post_types ) || in_array( $current_post_type, $allowed_post_types, true ) ) && ( is_single() || in_array( $current_post_type, array( 'page', 'attachment' ), true ) ) ) {
			if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();// support for contact form 7 script.
			}

			// Enqueue Vue and Ajax related library.
			wp_enqueue_script( $this->plugin_name . '_vue_2_4_4_js', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/vue.js', array(), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '_axios', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/axios.min.js', array(), $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_qs_js', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/qs.js', array(), $this->version, true );

			wp_enqueue_script( $this->plugin_name . '_custom_vue_script_js', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/commons/js/vue-script.js', array( $this->plugin_name . '_vue_2_4_4_js', $this->plugin_name . '_axios', $this->plugin_name . '_qs_js' ), $this->version, true );

			$footer_content = trim( sanitize_kt12word_content( get_option( 'kt12word_footer_content', '' ) ) );

			wp_localize_script(
				$this->plugin_name . '_custom_vue_script_js',
				'vue_object',
				array(
					'ajax_url'       => admin_url( 'admin-ajax.php' ),
					'security_id'    => wp_create_nonce( 'kt12words-by-id-nonce' ),
					'footer_content' => $footer_content,
				)
			);
		}
	}

	/**
	 * Searach for words in the content and mark them
	 *
	 * @since    1.0.0
	 * @param string $content post type content.
	 * @return string $content filtered content.
	 */
	public function mark_words_in_the_content( $content ) {
		$current_post_type = get_post_type();
		if ( is_single() || in_array( $current_post_type, array( 'page', 'attachment' ), true ) ) {
			global $wpdb;
			// $words = wp_cache_get( 'kt12words_content_words' );
			// if ( false === $result ) {
				$words = $wpdb->get_results( "SELECT id, word, length(word) as len FROM {$wpdb->prefix}kt12words_content ORDER BY len DESC", ARRAY_A );
			// wp_cache_set( 'kt12words_content_words', $words );
			// }
			foreach ( $words as $word ) {
				// Thanks to @wp78de from stack overflow for the regex .
				// refs:https://stackoverflow.com/questions/47118851/ignore-a-specific-tag-in-regex-negitive-lookahead .
				$pattern            = '/<kt12word-markup.*?<\/kt12word-markup>(*SKIP)(*F)|(' . str_replace( ' ', '(&nbsp;|[\\s\\p{Z}\\p{C}\\x85\\xA0\\x{0085}\x{00A0}\\x{FFFD}]+)*', $word['word'] ) . ')/iu';
				$replace            = '<kt12word-markup word-id="' . $word['id'] . '" title="' . esc_html__( 'Click here to know more.', 'kt12words-content' ) . '">$1</kt12word-markup>';
				$limit              = get_option( 'kt12word_highlight_count', '-1' );
				$validation_options = array(
					'options' => array(
						'default'   => -1,
						'min_range' => -1,
					),
				);
				$count              = filter_var( $limit, FILTER_VALIDATE_INT, $validation_options );
				$content            = preg_replace( $pattern, $replace, $content, $count );
			}
			$content = '<div id="kt12word-root">' . $content . '<transition enter-active-class="animated slideInRight"
        leave-active-class="animated slideOutRight"><kt12word-sidebar v-show="showSidebar"><template slot="footer"></template></kt12word-sidebar></transition></div>';
		}
		return $content;
	}
}
