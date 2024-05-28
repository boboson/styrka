<?php

class Styrka_Assessment_Import {
    public function __construct() {
        add_action( 'admin_post_import_assessment_data', array( $this, 'import_assessment_data' ) );
    }

    public function import_assessment_data() {
        check_admin_referer( 'styrka_import_nonce', 'styrka_import_nonce_field' );

        if ( ! isset( $_FILES['assessment_csv'] ) ) {
            wp_redirect( admin_url( 'admin.php?page=import-assessment-data&error=1' ) );
            exit;
        }

        $file = $_FILES['assessment_csv']['tmp_name'];
        $csv_data = array_map( 'str_getcsv', file( $file ) );
        $header = array_shift( $csv_data );

        global $wpdb;
        $table_name = $wpdb->prefix . 'styrka_grading_criteria';

        foreach ( $csv_data as $row ) {
            if (count($row) !== count($header)) {
                // Handle the error: log it, skip it, etc.
                continue; // Skip this row
            }

            $data = array_combine( $header, $row );
            if ($data === false) {
                continue; // Skip this row if array_combine fails
            }

            $wpdb->insert( $table_name, array(
                'category'   => sanitize_text_field( $data['Category'] ),
                'exercise'   => sanitize_text_field( $data['Exercise'] ),
                'gender'     => sanitize_text_field( $data['Gender'] ),
                'age_group'  => sanitize_text_field( $data['Age Group'] ),
                'max_score'  => floatval( $data['Max Score'] ),
                'unit'       => sanitize_text_field( $data['Unit'] ),
            ));
        }

        wp_redirect( admin_url( 'admin.php?page=import-assessment-data&import=success' ) );
        exit;
    }
}
?>
