<?php

class Styrka_Assessment_Charts {
    public function __construct() {
        add_shortcode( 'styrka_assessment_charts', array( $this, 'display_charts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function display_charts() {
        ob_start();
        ?>
        <canvas id="assessmentChart"></canvas>
        <script>
            jQuery(document).ready(function($) {
                var ctx = document.getElementById('assessmentChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Exercise 1', 'Exercise 2', 'Exercise 3'],
                        datasets: [{
                            label: 'Results',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [10, 20, 30]
                        }]
                    },
                    options: {}
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true );
    }
}
