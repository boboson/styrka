<?php

class Styrka_Assessment_BuddyBoss {
    public function __construct() {
        add_action( 'bp_setup_nav', array( $this, 'add_assessment_tab' ) );
    }

    public function add_assessment_tab() {
        global $bp;
        bp_core_new_nav_item( array(
            'name' => __( 'Assessments', 'styrka-athlete-assessment' ),
            'slug' => 'assessments',
            'position' => 10,
            'screen_function' => array( $this, 'assessment_tab_content' ),
            'default_subnav_slug' => 'assessments',
            'show_for_displayed_user' => true,
            'item_css_id' => 'assessments',
        ) );
    }

    public function assessment_tab_content() {
        add_action( 'bp_template_content', array( $this, 'assessment_tab_display' ) );
        bp_core_load_template( 'members/single/plugins' );
    }

    public function assessment_tab_display() {
        echo do_shortcode( '[styrka_assessment_form]' );
    }
}

new Styrka_Assessment_BuddyBoss();
