<?php

class Styrka_Assessment_Admin {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }

    public function add_admin_menu() {
        add_submenu_page(
            'styrka-community',
            __( 'Athlete Assessments', 'styrka-athlete-assessment' ),
            __( 'Athlete Assessments', 'styrka-athlete-assessment' ),
            'manage_options',
            'styrka-athlete-assessments',
            array( $this, 'display_assessment_page' )
        );

        add_submenu_page(
            'styrka-community',
            __( 'Import Assessment Data', 'styrka-athlete-assessment' ),
            __( 'Import Assessment Data', 'styrka-athlete-assessment' ),
            'manage_options',
            'import-assessment-data',
            array( $this, 'display_import_page' )
        );
    }

    public function display_assessment_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Athlete Assessments', 'styrka-athlete-assessment' ); ?></h1>
            <table id="assessments-table" class="display">
                <thead>
                    <tr>
                        <th><?php _e( 'User ID', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Assessment Type', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Category', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Exercise', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Result', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Max Score', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Grade', 'styrka-athlete-assessment' ); ?></th>
                        <th><?php _e( 'Date', 'styrka-athlete-assessment' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'styrka_assessment_results';
                    $results = $wpdb->get_results( "SELECT * FROM $table_name" );

                    foreach ( $results as $result ) {
                        echo '<tr>';
                        echo '<td>' . esc_html( $result->user_id ) . '</td>';
                        echo '<td>' . esc_html( $result->assessment_type ) . '</td>';
                        echo '<td>' . esc_html( $result->category ) . '</td>';
                        echo '<td>' . esc_html( $result->exercise ) . '</td>';
                        echo '<td>' . esc_html( $result->result ) . '</td>';
                        echo '<td>' . esc_html( $result->max_score ) . '</td>';
                        echo '<td>' . esc_html( $result->grade ) . '</td>';
                        echo '<td>' . esc_html( $result->created_at ) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function display_import_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Import Assessment Data', 'styrka-athlete-assessment' ); ?></h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <input type="hidden" name="action" value="import_assessment_data">
                <?php wp_nonce_field( 'styrka_import_nonce', 'styrka_import_nonce_field' ); ?>
                <input type="file" name="assessment_csv" accept=".csv">
                <input type="submit" value="<?php _e( 'Import CSV', 'styrka-athlete-assessment' ); ?>">
            </form>
        </div>
        <?php
    }
}
?>
