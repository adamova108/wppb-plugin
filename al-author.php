<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Al_Author
 *
 * @wordpress-plugin
 * Plugin Name:       AL Author
 * Description:       This is a demo plugin.
 * Version:           1.0.0
 * Author:            Adam Luzsi
 * Author URI:        https://github.com/adamova108
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       al-author
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AL_AUTHOR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-al-author-activator.php
 */
function activate_al_author() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-al-author-activator.php';
	Al_Author_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-al-author-deactivator.php
 */
function deactivate_al_author() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-al-author-deactivator.php';
	Al_Author_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_al_author' );
register_deactivation_hook( __FILE__, 'deactivate_al_author' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-al-author.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_al_author() {

	$plugin = new Al_Author();
	$plugin->run();

}
run_al_author();
