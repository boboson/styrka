<?php
/**
 * Plugin Name: Styrka Statistical Overview Add-on
 * Description: Add-on for displaying statistical overviews of group development in the Styrka Community.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-stats
 */

// Ensure the file is being run within the context of WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the base plugin is active
if (!class_exists('StyrkaCommunityBase')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Styrka Community Base Plugin is required for the Statistical Overview Add-on.</p></div>';
    });
    return;
}

// Main plugin class
class StyrkaStatisticalOverviewAddon {
    public function __construct() {
        add_shortcode('statistical_overview', [$this, 'render_stats']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function render_stats() {
        ob_start();
        ?>
        <div id="statistical-overview"></div>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('styrka-stats-style', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_script('styrka-stats-script', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], null, true);
    }
}

// Initialize the plugin
new StyrkaStatisticalOverviewAddon();
