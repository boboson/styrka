<?php
/**
 * Plugin Name: Styrka Athlete Assessment
 * Description: Add-on for the Styrka Community plugin to manage athlete assessments.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-athlete-assessment
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define constants.
define('STYRKA_ASSESSMENT_VERSION', '1.0');
define('STYRKA_ASSESSMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('STYRKA_ASSESSMENT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Ensure the base plugin is active
function styrka_assessment_check_base_plugin() {
    if (!is_plugin_active('styrka-community/styrka-community.php')) {
        add_action('admin_notices', 'styrka_assessment_base_plugin_notice');
        deactivate_plugins(plugin_basename(__FILE__));
    }
}
add_action('admin_init', 'styrka_assessment_check_base_plugin');

function styrka_assessment_base_plugin_notice() {
    echo '<div class="error"><p>' . __('Styrka Athlete Assessment requires the Styrka Community plugin to be installed and activated.', 'styrka-athlete-assessment') . '</p></div>';
}

// Initialize the plugin.
function styrka_assessment_init() {
    error_log('Styrka Athlete Assessment: Initializing plugin.');
}
add_action('plugins_loaded', 'styrka_assessment_init');

// Register admin menus.
function styrka_assessment_admin_menu() {
    error_log('Styrka Athlete Assessment: Adding admin menu.');

    add_menu_page(
        __('Styrka Athlete Assessment', 'styrka-athlete-assessment'),
        __('Athlete Assessment', 'styrka-athlete-assessment'),
        'manage_options',
        'styrka-athlete-assessment',
        'styrka_assessment_dashboard_page',
        'dashicons-analytics'
    );

    add_submenu_page(
        'styrka-athlete-assessment',
        __('Import Assessment Data', 'styrka-athlete-assessment'),
        __('Import Data', 'styrka-athlete-assessment'),
        'manage_options',
        'import-assessment-data',
        'styrka_assessment_import_page'
    );

    add_submenu_page(
        'styrka-athlete-assessment',
        __('Athlete Assessments', 'styrka-athlete-assessment'),
        __('Assessments', 'styrka-athlete-assessment'),
        'manage_options',
        'styrka-athlete-assessments',
        'styrka_assessment_manage_assessments_page'
    );
}
add_action('admin_menu', 'styrka_assessment_admin_menu');

function styrka_assessment_dashboard_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap"><h1>' . esc_html__('Styrka Athlete Assessment Dashboard', 'styrka-athlete-assessment') . '</h1>';
}

function styrka_assessment_import_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap"><h1>' . esc_html__('Import Assessment Data', 'styrka-athlete-assessment') . '</h1>';
    // Add the form for importing CSV files here.
}

function styrka_assessment_manage_assessments_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap"><h1>' . esc_html__('Athlete Assessments', 'styrka-athlete-assessment') . '</h1>';
    // Add the content for managing assessments here.
}
