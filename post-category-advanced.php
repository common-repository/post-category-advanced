<?php

/**
 *
 *
 * @link              https://all-wp.com
 * @since             1.0.0
 * @package           Post_Category_Advanced
 *
 * @wordpress-plugin
 * Plugin Name:       Post Category Advanced
 * Description:       Create relationships between post categories and tags, and more.
 * Version:           1.0.1
 * Author:            all-wp.com
 * Author URI:        https://all-wp.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-category-advanced
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
define( 'PCADV_VERSION', 'v. 1.0.0' );
// filemtime( plugin_dir_path( __FILE__ ) . 'post-category-advanced.php' )

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-category-advanced-activator.php
 */
function activate_pcadv() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-category-advanced-activator.php';
	Pcadv_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-category-advanced-deactivator.php
 */
function deactivate_pcadv() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-category-advanced-deactivator.php';
	Pcadv_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pcadv' );
register_deactivation_hook( __FILE__, 'deactivate_pcadv' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-category-advanced.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pcadv() {

	$plugin = new Post_Category_Advanced();
	$plugin->run();

}
run_pcadv();