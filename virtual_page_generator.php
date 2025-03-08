<?php
/**
 * Plugin Name: Virtual Page Generator
 * Plugin URI: https://yourwebsite.com/virtual-page-generator
 * Description: Generate virtual pages programmatically from a CSV file and manage dynamic sitemaps for SEO optimization.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: CloudApper
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: virtual-page-generator
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
if ( ! defined( 'VPG_PLUGIN_DIR' ) ) {
    define( 'VPG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'VPG_PLUGIN_URL' ) ) {
    define( 'VPG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}



// Include setting page
include(VPG_PLUGIN_DIR . '/includes/setting-page.php');

// Include plugin home
include(VPG_PLUGIN_DIR . 'includes/plugin-home.php');

// Include post type
include(VPG_PLUGIN_DIR . '/includes/post-type.php');

// Include plugin assets
include(VPG_PLUGIN_DIR . 'includes/plugin-assets.php');

// Include sitemap generator
include(VPG_PLUGIN_DIR . 'includes/sitemap-generator.php');

// Include virtual pages
include(VPG_PLUGIN_DIR . 'includes/virtual-pages.php');


register_activation_hook(__FILE__, 'vpg_activate_plugin');
register_deactivation_hook(__FILE__, 'vpg_deactivate_plugin');

function vpg_activate_plugin() {
    vpg_add_rewrite_rules();
    flush_rewrite_rules();
}

function vpg_deactivate_plugin() {
    flush_rewrite_rules();
}






