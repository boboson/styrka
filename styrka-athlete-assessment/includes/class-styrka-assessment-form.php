<?php

class Styrka_Assessment_Form {
    public function __construct() {
        add_shortcode( 'styrka_assessment_form', array( $this, 'display_assessment_form' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_submit_assessment', array( $this, 'handle_form_submission' ) );
        add_action( 'wp_ajax_nopriv_submit_assessment', array( $this, 'handle_form_submission' ) );
    }

	public function display_assessment_form() {
		if ( ! is_user_logged_in() ) {
			return '<p>' . __( 'You need to be logged in to access this form.', 'styrka-athlete-assessment' ) . '</p>';
		}

		ob_start();
		?>
		<form id="styrka-assessment-form" method="POST" action="">
			<?php wp_nonce_field( 'styrka_assessment_nonce', 'styrka_assessment_nonce_field' ); ?>
			<label for="assessment-type"><?php _e( 'Assessment Type', 'styrka-athlete-assessment' ); ?></label>
			<select id="assessment-type" name="assessment_type">
				<option value="basic"><?php _e( 'Basic', 'styrka-athlete-assessment' ); ?></option>
				<option value="crossfit"><?php _e( 'CrossFit', 'styrka-athlete-assessment' ); ?></option>
				<option value="hyrox"><?php _e( 'Hyrox', 'styrka-athlete-assessment' ); ?></option>
			</select>

			<div id="assessment-fields">
				<!-- Dynamically generate fields based on assessment type -->
			</div>

			<input type="submit" value="<?php _e( 'Submit Assessment', 'styrka-athlete-assessment' ); ?>">
		</form>
		<?php
		return ob_get_clean();
	}

    public function enqueue_scripts() {
        wp_enqueue_script( 'styrka-assessment-js', STYRKA_ASSESSMENT_PLUGIN_URL . 'assets/js/assessment.js', array( 'jquery' ), STYRKA_ASSESSMENT_VERSION, true );
        wp_enqueue_style( 'styrka-assessment-css', STYRKA_ASSESSMENT_PLUGIN_URL . 'assets/css/assessment.css', array(), STYRKA_ASSESSMENT_VERSION );
    }

    public function handle_form_submission() {
        check_ajax_referer( 'styrka_assessment_nonce', 'security' );

        $user_id = get_current_user_id();
        $assessment_type = sanitize_text_field( $_POST['assessment_type'] );
        $results = array_map( 'sanitize_text_field', $_POST['results'] );

        // Perform data analysis and grading
        $grades = $this->calculate_grades( $results, $user_id );

        // Save to database
        global $wpdb;
        $wpdb->insert( $wpdb->prefix . 'styrka_assessments', array(
            'athlete_id' => $user_id,
            'assessment_type' => $assessment_type,
            'created_at' => current_time( 'mysql' ),
        ));
        $assessment_id = $wpdb->insert_id;

        foreach ( $results as $category => $exercises ) {
            foreach ( $exercises as $exercise => $result ) {
                $max_score = $this->get_max_score( $category, $exercise, $user_id );
                $grade = $grades[$category][$exercise];

                $wpdb->insert( $wpdb->prefix . 'styrka_assessment_results', array(
                    'assessment_id' => $assessment_id,
                    'category' => $category,
                    'exercise' => $exercise,
                    'result' => $result,
                    'max_score' => $max_score,
                    'grade' => $grade,
                ));
            }
        }

        wp_send_json_success( array( 'message' => __( 'Assessment submitted successfully!', 'styrka-athlete-assessment' ) ) );
    }

    private function get_max_score( $category, $exercise, $user_id ) {
        global $wpdb;

        $user_gender = xprofile_get_field_data( 'Gender', $user_id );
        $user_age_group = xprofile_get_field_data( 'Age Group', $user_id );

        return $wpdb->get_var( $wpdb->prepare(
            "SELECT max_score FROM {$wpdb->prefix}styrka_grading_criteria WHERE category = %s AND exercise = %s AND gender = %s AND age_group = %s",
            $category, $exercise, $user_gender, $user_age_group
        ));
    }

    private function calculate_grades( $results, $user_id ) {
        $grades = array();
        foreach ( $results as $category => $exercises ) {
            foreach ( $exercises as $exercise => $result ) {
                $max_score = $this->get_max_score( $category, $exercise, $user_id );
                $grades[$category][$exercise] = $this->determine_grade( $result, $max_score );
            }
        }
        return $grades;
    }

    private function determine_grade( $result, $max_score ) {
        // Example grading logic, adjust as needed
        $percentage = ($result / $max_score) * 100;

        if ( $percentage >= 90 ) {
            return 'A';
        } elseif ( $percentage >= 80 ) {
            return 'B';
        } elseif ( $percentage >= 70 ) {
            return 'C';
        } elseif ( $percentage >= 60 ) {
            return 'D';
        } else {
            return 'F';
        }
    }
}
