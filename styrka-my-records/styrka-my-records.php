<?php
/**
 * Plugin Name: Styrka My Records Add-on
 * Description: Add-on for managing and tracking personal records in the Styrka Community.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-records
 */

// Ensure the file is being run within the context of WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the base plugin is active
if (!class_exists('StyrkaCommunityBase')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Styrka Community Base Plugin is required for the My Records Add-on.</p></div>';
    });
    return;
}

// Main plugin class
class StyrkaMyRecordsAddon {
    public function __construct() {
        add_action('init', [$this, 'register_record_post_type']);
        add_shortcode('my_records', [$this, 'render_records']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function register_record_post_type() {
        register_post_type('record', [
            'labels' => [
                'name' => __('Records', 'styrka-records'),
                'singular_name' => __('Record', 'styrka-records')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments']
        ]);
    }

    public function render_records() {
        ob_start();
        ?>
        <div id="my-records"></div>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('styrka-records-style', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_script('styrka-records-script', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], null, true);
    }
}

// Initialize the plugin
new StyrkaMyRecordsAddon();
