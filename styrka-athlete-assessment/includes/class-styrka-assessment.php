<?php

class Styrka_Assessment {
    public function __construct() {
        add_action( 'init', array( $this, 'register_assessment_post_type' ) );
        add_shortcode( 'styrka_assessment_form', array( $this, 'display_assessment_form' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function register_assessment_post_type() {
        $labels = array(
            'name'               => _x( 'Assessments', 'post type general name', 'styrka-athlete-assessment' ),
            'singular_name'      => _x( 'Assessment', 'post type singular name', 'styrka-athlete-assessment' ),
            'menu_name'          => _x( 'Assessments', 'admin menu', 'styrka-athlete-assessment' ),
            'name_admin_bar'     => _x( 'Assessment', 'add new on admin bar', 'styrka-athlete-assessment' ),
            'add_new'            => _x( 'Add New', 'assessment', 'styrka-athlete-assessment' ),
            'add_new_item'       => __( 'Add New Assessment', 'styrka-athlete-assessment' ),
            'new_item'           => __( 'New Assessment', 'styrka-athlete-assessment' ),
            'edit_item'          => __( 'Edit Assessment', 'styrka-athlete-assessment' ),
            'view_item'          => __( 'View Assessment', 'styrka-athlete-assessment' ),
            'all_items'          => __( 'All Assessments', 'styrka-athlete-assessment' ),
            'search_items'       => __( 'Search Assessments', 'styrka-athlete-assessment' ),
            'parent_item_colon'  => __( 'Parent Assessments:', 'styrka-athlete-assessment' ),
            'not_found'          => __( 'No assessments found.', 'styrka-athlete-assessment' ),
            'not_found_in_trash' => __( 'No assessments found in Trash.', 'styrka-athlete-assessment' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'assessment' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author' ),
        );

        register_post_type( 'assessment', $args );
    }

    public function display_assessment_form() {
        ob_start();
        ?>
        <form id="styrka-assessment-form">
            <label for="assessment-type"><?php _e( 'Assessment Type', 'styrka-athlete-assessment' ); ?></label>
            <select id="assessment-type" name="assessment_type">
                <option value="basic"><?php _e( 'Basic', 'styrka-athlete-assessment' ); ?></option>
                <option value="crossfit"><?php _e( 'CrossFit', 'styrka-athlete-assessment' ); ?></option>
                <option value="hyrox"><?php _e( 'Hyrox', 'styrka-athlete-assessment' ); ?></option>
            </select>

            <label for="results"><?php _e( 'Results', 'styrka-athlete-assessment' ); ?></label>
            <input type="text" id="results" name="results" placeholder="<?php _e( 'Enter your results', 'styrka-athlete-assessment' ); ?>">

            <input type="submit" value="<?php _e( 'Submit Assessment', 'styrka-athlete-assessment' ); ?>">
        </form>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'styrka-assessment-js', STYRKA_ASSESSMENT_PLUGIN_URL . 'assets/js/assessment.js', array( 'jquery' ), STYRKA_ASSESSMENT_VERSION, true );
        wp_enqueue_style( 'styrka-assessment-css', STYRKA_ASSESSMENT_PLUGIN_URL . 'assets/css/assessment.css', array(), STYRKA_ASSESSMENT_VERSION );
    }
}
