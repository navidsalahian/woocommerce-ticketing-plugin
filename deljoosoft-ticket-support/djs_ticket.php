<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://deljoosoft.com
 * @since             1.0.0
 * @package           Djs_Ticket_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Deljoosoft Ticket Manager
 * Plugin URI:        https://deljoosoft.com
 * Description:       Add ticket capability and extra features to WordPress and WooCommerce.
 * Version:           1.0.0
 * Author:            DeljooSoft Team
 * Author URI:        https://deljoosoft.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       djs_ticket_manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

require_once( ABSPATH. 'wp-admin/includes/plugin.php' );
$plugin_data = get_plugin_data(__FILE__ );
define( 'DJS_TICKET_VERSION', '1.0.0' );
define( 'DJS_TICKET_PATH', plugin_dir_path( __FILE__ ) );
define( 'DJS_TICKET_URL', plugin_dir_url(__FILE__) );
define( 'DJS_TICKET_PLUGIN', plugin_basename( __FILE__  ) );
define( 'DJS_TICKET_NAME', $plugin_data['Name'] );
define( 'DJS_TICKET_DOMAIN', 'deljoosoft-ticket-manager' );
define( 'DJS_TICKET_ADMIN_TPL', plugin_dir_path(__FILE__) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'templates' );
define( 'DJS_TICKET_PUBLIC_TPL', plugin_dir_path(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates' );
define( 'DJS_TICKET_INC', plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' );
define( 'DJS_TICKET_ASSETS', __FILE__ . DIRECTORY_SEPARATOR . 'assets' );
define( 'DJS_TICKET_USER_IMAGE_UPLOAD_DIR', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'upload_ticket_images' . DIRECTORY_SEPARATOR );
define( 'DJS_TICKET_USER_IMAGE_UPLOAD_URL', wp_upload_dir()['baseurl'] . DIRECTORY_SEPARATOR . 'upload_ticket_images' . DIRECTORY_SEPARATOR );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-djs_ticket-activator.php
 */
function activate_djs_ticket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-djs_ticket-activator.php';
	DJS_Ticket_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-djs_ticket-deactivator.php
 */
function deactivate_djs_ticket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-djs_ticket-deactivator.php';
	DJS_Ticket_Deactivator::deactivate();
}

register_activation_hook( DJS_TICKET_PLUGIN, 'activate_djs_ticket' );
register_deactivation_hook( DJS_TICKET_PLUGIN, 'deactivate_djs_ticket' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-djs_ticket.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_djs_ticket() {

	$plugin = new DJS_Ticket();
	$plugin->run();

}

add_action('plugins_loaded', 'djs_ticket_plugins_loaded__handler');
function djs_ticket_plugins_loaded__handler(){
    if (! defined('WC_PLUGIN_FILE') || ! key_exists('woocommerce/woocommerce.php', get_plugins()) ) {

        add_action( 'admin_notices', 'djs_ticket_dependencies__handler' );
        function djs_ticket_dependencies__handler() {
            echo '<div class="error"><p>' . sprintf( 'The <strong>%s</strong> extension requires the WooCommerce plugin to be installed to work properly.', DJS_TICKET_NAME ) . '</p></div>';
        }

    } else {
        if (! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            add_action( 'admin_notices', 'djs_ticket_dependencies__handler' );
            function djs_ticket_dependencies__handler() {
                echo '<div class="error"><p>' . sprintf( 'The <strong>%s</strong> extension requires the WooCommerce plugin to be activated to work properly.', DJS_TICKET_NAME ) . '</p></div>';
            }
        }
        else {
            run_djs_ticket();
        }
    }
}

