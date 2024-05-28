<?php

class Styrka_Assessment_Form {
    public function __construct() {
        add_action('wp_ajax_get_assessment_fields', array($this, 'get_assessment_fields'));
        add_action('wp_ajax_nopriv_get_assessment_fields', array($this, 'get_assessment_fields'));
        add_action('wp_ajax_save_assessment_data', array($this, 'save_assessment_data'));
        add_action('wp_ajax_nopriv_save_assessment_data', array($this, 'save_assessment_data'));
    }

    public function get_assessment_fields() {
        if (isset($_POST['type'])) {
            $assessment_type = sanitize_text_field($_POST['type']);
            global $wpdb;
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}styrka_grading_criteria WHERE category = %s", 
                $assessment_type
            );
            $criteria = $wpdb->get_results($query);

            error_log("Executing query: " . $query);
            error_log("Query results: " . print_r($criteria, true));

            if ($criteria) {
                $html = '';
                foreach ($criteria as $criterion) {
                    $html .= '<div class="assessment-criterion">';
                    $html .= '<label>' . esc_html($criterion->exercise) . ' (' . esc_html($criterion->unit) . '):</label>';
                    $html .= '<input type="number" name="results[' . esc_attr($criterion->category) . '][' . esc_attr($criterion->exercise) . ']" step="any" required>';
                    $html .= '</div>';
                }
                wp_send_json_success(['html' => $html]);
            } else {
                wp_send_json_error(['message' => 'No criteria found for the selected assessment type.']);
            }
        } else {
            wp_send_json_error(['message' => 'Assessment type not provided.']);
        }
    }

    public function save_assessment_data() {
        if (isset($_POST['assessment_type']) && isset($_POST['results']) && is_user_logged_in()) {
            global $wpdb;
            $user_id = get_current_user_id();
            $assessment_type = sanitize_text_field($_POST['assessment_type']);
            $results = $_POST['results'];
            
            // Insert assessment data
            $wpdb->insert(
                "{$wpdb->prefix}styrka_assessments",
                [
                    'athlete_id' => $user_id,
                    'assessment_type' => $assessment_type,
                    'created_at' => current_time('mysql')
                ]
            );
            
            $assessment_id = $wpdb->insert_id;
            
            // Insert results data
            foreach ($results as $category => $exercises) {
                foreach ($exercises as $exercise => $result) {
                    $wpdb->insert(
                        "{$wpdb->prefix}styrka_assessment_results",
                        [
                            'assessment_id' => $assessment_id,
                            'category' => sanitize_text_field($category),
                            'exercise' => sanitize_text_field($exercise),
                            'result' => floatval($result),
                            'max_score' => 100, // Assuming a max score placeholder
                            'grade' => 'A', // Assuming a grade placeholder
                            'created_at' => current_time('mysql')
                        ]
                    );
                }
            }
            
            wp_send_json_success(['message' => 'Assessment data saved successfully.']);
        } else {
            wp_send_json_error(['message' => 'Invalid data or not logged in.']);
        }
    }
}

new Styrka_Assessment_Form();
?>
