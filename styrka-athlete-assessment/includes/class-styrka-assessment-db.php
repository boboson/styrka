<?php

class Styrka_Assessment_DB {
    public function __construct() {
        register_activation_hook( __FILE__, array( $this, 'create_tables' ) );
    }

    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            "CREATE TABLE {$wpdb->prefix}styrka_athletes (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                gender varchar(10) NOT NULL,
                age_group varchar(10) NOT NULL,
                body_weight float NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;",
            
            "CREATE TABLE {$wpdb->prefix}styrka_assessments (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                athlete_id mediumint(9) NOT NULL,
                assessment_type varchar(255) NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;",
            
            "CREATE TABLE {$wpdb->prefix}styrka_assessment_results (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                assessment_id mediumint(9) NOT NULL,
                category varchar(255) NOT NULL,
                exercise varchar(255) NOT NULL,
                result float NOT NULL,
                max_score float NOT NULL,
                grade varchar(10) NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;",
            
            "CREATE TABLE {$wpdb->prefix}styrka_categories (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;",
            
            "CREATE TABLE {$wpdb->prefix}styrka_exercises (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                category_id mediumint(9) NOT NULL,
                name varchar(255) NOT NULL,
                max_score float NOT NULL,
                unit varchar(10) NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;",
            
            "CREATE TABLE {$wpdb->prefix}styrka_grading_criteria (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				category varchar(255) NOT NULL,
				exercise varchar(255) NOT NULL,
				gender varchar(10) NOT NULL,
				age_group varchar(10) NOT NULL,
				max_score float NOT NULL,
				unit varchar(10) NOT NULL,
				PRIMARY KEY (id)
            ) $charset_collate;"
        ];

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        foreach ( $tables as $sql ) {
			dbDelta( $sql );
		}
	}
}
