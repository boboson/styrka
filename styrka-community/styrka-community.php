<?php
/**
 * Plugin Name: Styrka Community
 * Description: The base plugin for the Styrka Community.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-community
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define constants.
define('STYRKA_COMMUNITY_VERSION', '1.0');
define('STYRKA_COMMUNITY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('STYRKA_COMMUNITY_PLUGIN_URL', plugin_dir_url(__FILE__));

// Initialize the plugin.
function styrka_community_init() {
    error_log('Styrka Community: Initializing plugin.');
}
add_action('plugins_loaded', 'styrka_community_init');

// Register admin menus.
function styrka_community_admin_menu() {
    error_log('Styrka Community: Adding admin menu.');

    add_menu_page(
        __('Styrka Community', 'styrka-community'),
        __('Styrka Community', 'styrka-community'),
        'manage_options',
        'styrka-community',
        'styrka_community_dashboard_page',
        'dashicons-admin-generic'
    );
}
add_action('admin_menu', 'styrka_community_admin_menu');

function styrka_community_dashboard_page() {
    error_log('Styrka Community: Displaying dashboard page.');
    echo '<div class="wrap"><h1>' . esc_html__('Styrka Community Dashboard', 'styrka-community') . '</h1></div>';
}
