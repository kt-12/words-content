<?php

/**
 * The ajax calls of the plugin.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/ajax
 */

/**
 * The ajax calls of the plugin.
 *
 * @package    Words_Content
 * @subpackage Words_Content/ajax
 * @author     Karthik Thayyil <me@kt12.in>
 */
class Kt12Words_Content_Ajax {
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
	 * Ajax call to retrive word's content by id.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function ajax_to_get_word_content_by_id() {
		check_ajax_referer( 'kt12words-by-id-nonce', 'security' );

		global $wpdb;
		$word_id = filter_input( INPUT_POST, 'kt12word_word_id', FILTER_VALIDATE_INT );
		if ( $word_id ) {
			$word = $wpdb->get_row( $wpdb->prepare( "select id as word_id , title, content from {$wpdb->prefix}kt12words_content where id=%d", $word_id ), ARRAY_A );
			if ( ! empty( $word ) ) {
				$word['content'] = apply_filters( 'the_content', $word['content'] );
				$response        = array(
					'status' => 1,
					'word'   => $word,
				);
			} else {
				$response = array(
					'status'  => 0,
					'message' => __( 'Word not found.', 'kt12words-content' ),
				);
			}
		} else {
			$response = array(
				'status'  => 0,
				'message' => __( 'Illegal access.', 'kt12words-content' ),
			);
		}
		wp_send_json( $response );
	}

	/**
	 * Ajax to process raw content.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function ajax_to_process_raw_content() {
		check_ajax_referer( 'kt12words-by-content-nonce', 'security' );

		global $wpdb;
		$content = filter_input( INPUT_POST, 'kt12word_content', FILTER_DEFAULT );

		if ( ! empty( trim( $content ) ) ) {
			$content  = apply_filters( 'the_content', $content );
			$response = array(
				'status'  => 1,
				'content' => $content,
			);
		} else {
			$response = array( 'status' => 0 );
		}
		wp_send_json( $response );
	}
}
