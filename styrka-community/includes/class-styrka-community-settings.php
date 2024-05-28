<?php

class Styrka_Community_Settings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        // Add additional hooks if needed
    }

    public function add_settings_page() {
        add_menu_page(
            'Styrka Community Settings',
            'Styrka Community',
            'manage_options',
            'styrka-community',
            array( $this, 'settings_page_content' ),
            'dashicons-admin-generic'
        );
    }

    public function settings_page_content() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Styrka Community Settings', 'styrka-community' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'styrka_community_settings' );
                do_settings_sections( 'styrka-community' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    // Additional methods for settings can be added here if needed
}
