<?php
/**
 * Plugin Name: Styrka Year Calendar Add-on
 * Description: Add-on for managing year-long events and competitions in the Styrka Community.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-calendar
 */

// Ensure the file is being run within the context of WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the base plugin is active
if (!class_exists('StyrkaCommunityBase')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Styrka Community Base Plugin is required for the Year Calendar Add-on.</p></div>';
    });
    return;
}

// Main plugin class
class StyrkaYearCalendarAddon {
    public function __construct() {
        add_action('init', [$this, 'register_event_post_type']);
        add_shortcode('year_calendar', [$this, 'render_calendar']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function register_event_post_type() {
        register_post_type('event', [
            'labels' => [
                'name' => __('Events', 'styrka-calendar'),
                'singular_name' => __('Event', 'styrka-calendar')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments']
        ]);
    }

    public function render_calendar() {
        ob_start();
        ?>
        <div id="year-calendar"></div>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('styrka-calendar-style', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_script('styrka-calendar-script', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], null, true);
    }
}

// Initialize the plugin
new StyrkaYearCalendarAddon();
