<?php

class Styrka_Assessment_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_get_assessment_fields', array( $this, 'get_assessment_fields' ) );
        add_action( 'wp_ajax_nopriv_get_assessment_fields', array( $this, 'get_assessment_fields' ) );
    }

    public function get_assessment_fields() {
        check_ajax_referer( 'styrka_assessment_nonce', 'security' );

        $assessment_type = sanitize_text_field( $_POST['assessment_type'] );
        $html = '';

        global $wpdb;
        $categories = $wpdb->get_results( $wpdb->prepare(
            "SELECT DISTINCT category FROM {$wpdb->prefix}styrka_grading_criteria WHERE assessment_type = %s", $assessment_type
        ));

        foreach ( $categories as $category ) {
            $html .= '<h3>' . esc_html( $category->category ) . '</h3>';
            $exercises = $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}styrka_grading_criteria WHERE category = %s AND assessment_type = %s", $category->category, $assessment_type
            ));

            foreach ( $exercises as $exercise ) {
                $html .= '<label for="results[' . esc_attr( $category->category ) . '][' . esc_attr( $exercise->exercise ) . ']">' . esc_html( $exercise->exercise ) . ' (' . esc_html( $exercise->unit ) . ')</label>';
                $html .= '<input type="text" name="results[' . esc_attr( $category->category ) . '][' . esc_attr( $exercise->exercise ) . ']" id="results[' . esc_attr( $category->category ) . '][' . esc_attr( $exercise->exercise ) . ']" />';
            }
        }

        wp_send_json_success( array( 'html' => $html ) );
    }
}
