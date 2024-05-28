<?php
/**
 * Plugin Name: Styrka Athlete Assessment
 * Description: Add-on for the Styrka Community plugin to manage athlete assessments.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-athlete-assessment
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants.
define( 'STYRKA_ASSESSMENT_VERSION', '1.0' );
define( 'STYRKA_ASSESSMENT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'STYRKA_ASSESSMENT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files.
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-db.php';
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-form.php';
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-admin.php';
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-charts.php';
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-import.php';
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-ajax.php';
require_once STYRKA_ASSESSMENT_PLUGIN_DIR . 'includes/class-styrka-assessment-buddyboss.php';

// Initialize the plugin.
function styrka_assessment_init() {
    $db = new Styrka_Assessment_DB();
    $form = new Styrka_Assessment_Form();
    $admin = new Styrka_Assessment_Admin();
    $charts = new Styrka_Assessment_Charts();
    $import = new Styrka_Assessment_Import();
    $ajax = new Styrka_Assessment_Ajax();
    $buddyboss = new Styrka_Assessment_BuddyBoss();
}
add_action( 'plugins_loaded', 'styrka_assessment_init' );

// Enqueue scripts and styles
function styrka_assessment_enqueue_scripts() {
    wp_enqueue_script( 'styrka-assessment-js', STYRKA_ASSESSMENT_PLUGIN_URL . 'assets/js/assessment.js', array( 'jquery' ), STYRKA_ASSESSMENT_VERSION, true );
    wp_localize_script( 'styrka-assessment-js', 'styrkaAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_style( 'styrka-assessment-css', STYRKA_ASSESSMENT_PLUGIN_URL . 'assets/css/assessment.css', array(), STYRKA_ASSESSMENT_VERSION );
}
add_action( 'wp_enqueue_scripts', 'styrka_assessment_enqueue_scripts' );
