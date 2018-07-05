<?php
/**
 * //This plugin uses https://github.com/DevinVinson/WordPress-Plugin-Boilerplate plugin boilerplate with some modification
 *
 * @link              https://kt12.in
 * @since             1.0.0
 * @package           Words_Content
 *
 * @wordpress-plugin
 * Plugin Name:       Word's Content
 * Plugin URI:        https://kt12.in/words-content/#utm_source=wpadmin&utm_medium=plugin&utm_campaign=wordscontentplugin
 * Description:       A plugin that helps you explain things better and increases reader engagement, reducing bounce rate and creates direct leads from page.
 * Version:           1.0.1
 * Author:            Karthik Thayyil
 * Author URI:        https://kt12.in/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kt12words-content
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kt12words-content-activator.php
 */
function activate_kt12words_content() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kt12words-content-activator.php';
	$activator_class = new Kt12Words_Content_Activator();
	$activator_class->activate();
	unset( $activator_class );  // freeing memory.
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kt12words-content-deactivator.php
 */
function deactivate_kt12words_content() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kt12words-content-deactivator.php';
	$deactivator_class = new Kt12Words_Content_Deactivator();
	$deactivator_class->deactivate();
	unset( $deactivator_class );  // freeing memory.
}

register_activation_hook( __FILE__, 'activate_kt12words_content' );
register_deactivation_hook( __FILE__, 'deactivate_kt12words_content' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kt12words-content.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_kt12words_content() {
	$plugin = new Kt12Words_Content();
	$plugin->run();
}
run_kt12words_content();
