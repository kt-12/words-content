<?php
/**
 * Remove Script tag from the content
 *
 * @since    1.0.0
 * @param    string  $content  content to be sanitize.
 * @return  string  $content  sanitized content.
 */
if ( ! function_exists( 'sanitize_kt12word_content' ) ) {
	function sanitize_kt12word_content( $content ) {
		$allowed_tags = array(
			'a'          => array(
				'class'  => array(),
				'href'   => array(),
				'rel'    => array(),
				'title'  => array(),
				'target' => array(),
			),
			'abbr'       => array(
				'title' => array(),
			),
			'b'          => array(),
			'blockquote' => array(
				'cite' => array(),
			),
			'cite'       => array(
				'title' => array(),
			),
			'code'       => array(),
			'del'        => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'dd'         => array(),
			'div'        => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl'         => array(),
			'dt'         => array(),
			'em'         => array(),
			'h1'         => array(),
			'h2'         => array(),
			'h3'         => array(),
			'h4'         => array(),
			'h5'         => array(),
			'h6'         => array(),
			'i'          => array(),
			'img'        => array(
				'alt'    => array(),
				'class'  => array(),
				'height' => array(),
				'src'    => array(),
				'width'  => array(),
			),
			'li'         => array(
				'class' => array(),
				'type'  => array(),
				'value' => array(),
			),
			'ol'         => array(
				'class' => array(),
			),
			'p'          => array(
				'class' => array(),
			),
			'q'          => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'       => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'strike'     => array(),
			'strong'     => array(),
			'ul'         => array(
				'class' => array(),
			),
		);
		return wp_kses( $content, $allowed_tags );
	}
}
/**
 * Set Message to be shown on the immediate page
 *
 * since 1.0.0
 *
 * @param string $message message string
 * @param string $message message type string
 */
if ( ! function_exists( 'set_kt12word_content_msg' ) ) {
	function set_kt12word_content_msg( $message, $type ) {
		update_option(
			'kt12word_notices',
			array(
				'msg_type' => $type,
				'message'  => $message,
			),
			null
		);
	}
}

/**
 * since 1.0.0
 *
 * @return array message array with message and type of message.
 */
if ( ! function_exists( 'get_kt12word_content_msg' ) ) {
	function get_kt12word_content_msg() {
		$message = get_option( 'kt12word_notices', null );
		delete_option( 'kt12word_notices' );
		return $message;
	}
}
