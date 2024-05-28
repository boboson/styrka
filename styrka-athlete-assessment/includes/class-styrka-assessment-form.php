<?php

class Styrka_Assessment_Form {
    public function __construct() {
        add_shortcode('styrka_assessment_form', array($this, 'render_assessment_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_get_assessment_fields', array($this, 'get_assessment_fields'));
        add_action('wp_ajax_nopriv_get_assessment_fields', array($this, 'get_assessment_fields'));
        add_action('wp_ajax_submit_assessment', array($this, 'submit_assessment'));
        add_action('wp_ajax_nopriv_submit_assessment', array($this, 'submit_assessment'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('assessment-css', plugins_url('assets/css/assessment.css', dirname(__FILE__)), array(), '1.0');
        wp_enqueue_script('assessment-js', plugins_url('assets/js/assessment.js', dirname(__FILE__)), array('jquery'), '1.0', true);
        wp_localize_script('assessment-js', 'assessment_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }

    public function render_assessment_form() {
        ob_start(); ?>
        <form id="assessment-form">
            <label for="assessment-type">Select Assessment Type:</label>
            <select id="assessment-type" name="assessment_type">
                <option value="basic">Basic</option>
                <option value="crossfit">CrossFit</option>
                <option value="hyrox">Hyrox</option>
            </select>
            <div id="assessment-fields">
                <!-- Dynamically generated fields will appear here -->
            </div>
            <button type="button" id="submit-assessment">Submit Assessment</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function get_assessment_fields() {
        global $wpdb;
        $assessment_type = $_POST['assessment_type'];
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}styrka_grading_criteria WHERE category = %s",
            $assessment_type
        ));

        if (!empty($results)) {
            $fields_html = '';
            foreach ($results as $result) {
                $fields_html .= '<div class="form-group">';
                $fields_html .= '<label for="' . esc_attr($result->exercise) . '">' . esc_html($result->exercise) . ' (' . esc_html($result->unit) . '):</label>';
                $fields_html .= '<input type="number" id="' . esc_attr($result->exercise) . '" name="' . esc_attr($result->exercise) . '" max="' . esc_attr($result->max_score) . '" required>';
                $fields_html .= '</div>';
            }
            wp_send_json_success($fields_html);
        } else {
            wp_send_json_error('No fields found for this assessment type.');
        }
    }

    public function submit_assessment() {
        global $wpdb;
        $assessment_type = $_POST['assessment_type'];
        $athlete_id = get_current_user_id();
        $assessment_data = $_POST['assessment_data'];

        // Insert assessment
        $wpdb->insert(
            "{$wpdb->prefix}styrka_assessments",
            array(
                'athlete_id' => $athlete_id,
                'assessment_type' => $assessment_type,
            ),
            array('%d', '%s')
        );
        $assessment_id = $wpdb->insert_id;

        // Insert assessment results
        foreach ($assessment_data as $exercise => $result) {
            $wpdb->insert(
                "{$wpdb->prefix}styrka_assessment_results",
                array(
                    'assessment_id' => $assessment_id,
                    'exercise' => $exercise,
                    'result' => $result,
                ),
                array('%d', '%s', '%f')
            );
        }

        wp_send_json_success('Assessment submitted successfully.');
    }
}

new Styrka_Assessment_Form();
