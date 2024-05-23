<?php
/**
 * Plugin Name: Styrka Athlete Assessment Add-on
 * Description: Add-on for athlete assessments in the Styrka Community.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-assessment
 */

// Ensure the file is being run within the context of WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the base plugin is active
if (!class_exists('StyrkaCommunityBase')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Styrka Community Base Plugin is required for the Athlete Assessment Add-on.</p></div>';
    });
    return;
}

// Main plugin class
class StyrkaAthleteAssessmentAddon {
    public function __construct() {
        add_action('init', [$this, 'register_assessment_post_type']);
        add_shortcode('athlete_assessment_form', [$this, 'render_assessment_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function register_assessment_post_type() {
        register_post_type('assessment', [
            'labels' => [
                'name' => __('Assessments', 'styrka-assessment'),
                'singular_name' => __('Assessment', 'styrka-assessment')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments']
        ]);
    }

    public function render_assessment_form() {
        ob_start();
        ?>
        <form method="post" action="">
            <label for="assessment_type"><?php _e('Assessment Type', 'styrka-assessment'); ?></label>
            <select name="assessment_type" id="assessment_type">
                <option value="basic"><?php _e('Basic', 'styrka-assessment'); ?></option>
                <option value="crossfit"><?php _e('CrossFit', 'styrka-assessment'); ?></option>
                <option value="hyrox"><?php _e('Hyrox', 'styrka-assessment'); ?></option>
            </select>
            <!-- Add more fields as needed -->
            <input type="submit" value="<?php _e('Submit', 'styrka-assessment'); ?>">
        </form>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('styrka-assessment-style', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_script('styrka-assessment-script', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], null, true);
    }
}

// Initialize the plugin
new StyrkaAthleteAssessmentAddon();
