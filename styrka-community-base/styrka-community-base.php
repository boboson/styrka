<?php
/**
 * Plugin Name: Styrka Community Base Plugin
 * Description: Base plugin for the Styrka Community, integrated with BuddyBoss.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: styrka-community
 */

// Ensure the file is being run within the context of WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// Main plugin class
class StyrkaCommunityBase {
    public function __construct() {
        add_action('init', [$this, 'register_member_post_type']);
        add_action('admin_menu', [$this, 'add_preferences_page']);
        add_action('show_user_profile', [$this, 'extend_user_profile']);
        add_action('edit_user_profile', [$this, 'extend_user_profile']);
        add_action('personal_options_update', [$this, 'save_extended_profile']);
        add_action('edit_user_profile_update', [$this, 'save_extended_profile']);
    }

    public function register_member_post_type() {
        register_post_type('member', [
            'labels' => [
                'name' => __('Members', 'styrka-community'),
                'singular_name' => __('Member', 'styrka-community')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments']
        ]);
    }

    public function add_preferences_page() {
        add_options_page(
            __('Styrka Preferences', 'styrka-community'),
            __('Styrka Preferences', 'styrka-community'),
            'manage_options',
            'styrka-preferences',
            [$this, 'render_preferences_page']
        );
    }

    public function render_preferences_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Styrka Preferences', 'styrka-community'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('styrka_preferences_group');
                do_settings_sections('styrka-preferences');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function extend_user_profile($user) {
        ?>
        <h3><?php _e('Additional Information', 'styrka-community'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="my_gym"><?php _e('My Gym', 'styrka-community'); ?></label></th>
                <td><input type="text" name="my_gym" id="my_gym" value="<?php echo esc_attr(get_the_author_meta('my_gym', $user->ID)); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="programming"><?php _e('Programming', 'styrka-community'); ?></label></th>
                <td><input type="text" name="programming" id="programming" value="<?php echo esc_attr(get_the_author_meta('programming', $user->ID)); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="age"><?php _e('Age', 'styrka-community'); ?></label></th>
                <td><input type="number" name="age" id="age" value="<?php echo esc_attr(get_the_author_meta('age', $user->ID)); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="weight"><?php _e('Weight', 'styrka-community'); ?></label></th>
                <td><input type="number" name="weight" id="weight" value="<?php echo esc_attr(get_the_author_meta('weight', $user->ID)); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="height"><?php _e('Height', 'styrka-community'); ?></label></th>
                <td><input type="number" name="height" id="height" value="<?php echo esc_attr(get_the_author_meta('height', $user->ID)); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }

    public function save_extended_profile($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        update_user_meta($user_id, 'my_gym', sanitize_text_field($_POST['my_gym']));
        update_user_meta($user_id, 'programming', sanitize_text_field($_POST['programming']));
        update_user_meta($user_id, 'age', intval($_POST['age']));
        update_user_meta($user_id, 'weight', intval($_POST['weight']));
        update_user_meta($user_id, 'height', intval($_POST['height']));
    }
}

// Initialize the plugin
new StyrkaCommunityBase();
