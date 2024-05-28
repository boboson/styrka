<?php

class Styrka_Assessment_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_get_assessment_fields', array( $this, 'get_assessment_fields' ) );
        add_action( 'wp_ajax_nopriv_get_assessment_fields', array( $this, 'get_assessment_fields' ) );
    }

    public function get_assessment_fields() {
        check_ajax_referer( 'styrka_assessment_nonce', 'security' );

        if ( ! isset( $_POST['type'] ) ) {
            wp_send_json_error( array( 'message' => 'Assessment type not provided.' ) );
            return;
        }

        $type = sanitize_text_field( $_POST['type'] );

        global $wpdb;
        $fields = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}styrka_exercises WHERE category_id IN (SELECT id FROM {$wpdb->prefix}styrka_categories WHERE name = %s)",
            $type
        ));

        if ( empty( $fields ) ) {
            wp_send_json_error( array( 'message' => 'No fields found for the selected assessment type.' ) );
            return;
        }

        ob_start();
        foreach ( $fields as $field ) {
            ?>
            <label for="<?php echo esc_attr( $field->name ); ?>"><?php echo esc_html( $field->name ); ?></label>
            <input type="text" id="<?php echo esc_attr( $field->name ); ?>" name="results[<?php echo esc_attr( $field->category ); ?>][<?php echo esc_attr( $field->name ); ?>]">
            <?php
        }
        wp_send_json_success( ob_get_clean() );
    }
}
