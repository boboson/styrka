<?php

class Styrka_Assessment_Import {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_import_page' ) );
        add_action( 'admin_post_import_assessment_data', array( $this, 'import_assessment_data' ) );
    }

    public function add_import_page() {
        add_submenu_page(
            'styrka-community',
            __( 'Import Assessment Data', 'styrka-athlete-assessment' ),
            __( 'Import Assessment Data', 'styrka-athlete-assessment' ),
            'manage_options',
            'import-assessment-data',
            array( $this, 'display_import_page' )
        );
    }

    public function display_import_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Import Assessment Data', 'styrka-athlete-assessment' ); ?></h1>
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="import_assessment_data">
                <?php wp_nonce_field( 'import_assessment_data', 'import_assessment_data_nonce' ); ?>
                <input type="file" name="assessment_file" accept=".csv">
                <input type="submit" value="<?php _e( 'Import', 'styrka-athlete-assessment' ); ?>">
            </form>
        </div>
        <?php
    }

    public function import_assessment_data() {
        check_admin_referer( 'import_assessment_data', 'import_assessment_data_nonce' );

        if ( isset( $_FILES['assessment_file'] ) && $_FILES['assessment_file']['error'] == UPLOAD_ERR_OK ) {
            $file = $_FILES['assessment_file']['tmp_name'];
            $handle = fopen( $file, 'r' );

            if ( $handle !== FALSE ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'styrka_grading_criteria';

                // Skip the header row
                fgetcsv( $handle );

                while ( ( $row = fgetcsv( $handle, 1000, ',' ) ) !== FALSE ) {
                    $wpdb->insert( $table_name, array(
                        'category' => sanitize_text_field( $row[0] ),
                        'exercise' => sanitize_text_field( $row[1] ),
                        'gender' => sanitize_text_field( $row[2] ),
                        'age_group' => sanitize_text_field( $row[3] ),
                        'max_score' => floatval( $row[4] ),
                        'unit' => sanitize_text_field( $row[5] ),
                    ));
                }

                fclose( $handle );

                wp_redirect( admin_url( 'admin.php?page=import-assessment-data&import=success' ) );
                exit;
            }
        }

        wp_redirect( admin_url( 'admin.php?page=import-assessment-data&import=error' ) );
        exit;
    }
}
