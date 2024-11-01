<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tekniskera.com
 * @since             1.0.0
 * @package           wholesale-market-suit-for-woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Wholesale Market Suite for WooCommerce			
 * Plugin URI:        https://tekniskera.com/docs/wholesale-market-suit-for-woocommerce
 * Description:       Wholesale Market Suite for WooCommerce helps WooCommerce stores create and manage wholesale prices, and product as well as category wise discount  the woocommerce product.
 * Version:           1.0.0
 * Author:            Teknisk Era
 * Author URI:        https://www.tekniskera.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wholesale-market-suit-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('woocommerce/woocommerce.php')) {
    if(is_plugin_active('wholesale-market-suite-for-WooCommerce-pro/wholesale-market-suite-pro.php')){
        return;
    }
    else {
         /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path(__FILE__) . 'includes/class-tewms.php';
    tewms_run();
    }
   
} else {
    add_action('admin_notices', 'tewms_admin_notice');
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TEWMS_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tewms-activator.php
 */
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function tewms_run()
{

    $plugin = new Tewms();
    $plugin->tewms_run();

}
function tewms_admin_notice()
{
    ?>
    <div class="error">
        <p>
            <?php esc_html_e('wholesale market suit for woocommerce  plugin requires the WooCommerce plugin. Please install and activate WooCommerce first, then activate this plugin.', 'wholesale-market-suit-for-woocommerce'); ?>
        </p>
    </div>
    <?php
    deactivate_plugins(plugin_basename(__FILE__));
}