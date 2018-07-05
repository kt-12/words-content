<?php
/**
 * Fired during plugin activation
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Words_Content
 * @subpackage Words_Content/includes
 * @author     Karthik Thayyil <me@kt12.in>
 */
class Kt12Words_Content_Activator {
	/**
	 * Create/Updates tables for this plugin on activation.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		$this->create_tables();
		$this->set_default_options();
	}

	/**
	 * Call to created all the table.
	 *
	 * @since 1.0.0
	 */
	public function create_tables() {
		$this->create_table_kt12words_content();
	}

	/**
	 *  Word's Content Main Table
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function create_table_kt12words_content() {
		global $wpdb;

		$table_version = '1.0';

		$existing_version = get_option( 'kt12words_content_table_version', false );

		if ( ! $existing_version || strcmp( $table_version, $existing_version ) !== 0 ) {
			$table_name   = $wpdb->prefix . 'kt12words_content';
			$wpdb_collate = $wpdb->collate;
			$sql          = "CREATE TABLE {$table_name} (
                      id bigint(20) unsigned NOT NULL auto_increment,
                      word text NOT NULL,
                      title text NOT NULL,
                      content text NOT NULL,
                      created_by bigint(20) UNSIGNED NOT NULL DEFAULT '0',
                      created_at int(11) UNSIGNED NOT NULL DEFAULT '0',
                      updated_by bigint(20) UNSIGNED NOT NULL DEFAULT '0',
                      updated_at int(11) UNSIGNED NOT NULL DEFAULT '0',
                    PRIMARY KEY (id)
                )
                COLLATE {$wpdb_collate}";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			if ( dbDelta( $sql ) ) {
				add_option( 'kt12words_content_table_version', $table_version );
			}
		}
	}

	/**
	 *  Set Default option on plugin activation
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function set_default_options() {
		if ( get_option( 'kt12word_highlight_count' ) === false ) {
			update_option( 'kt12word_highlight_count', '-1' );
		}
		if ( get_option( 'kt12word_footer_content' ) === false ) {
			update_option( 'kt12word_footer_content', "Powered by <a href='https://kt12.in/words-content'> Word's Content</a>" );
		}
		add_option( 'kt12words_content_activated', true );
	}
}
