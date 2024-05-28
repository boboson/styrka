jQuery(document).ready(function($) {
    $('#assessment-type').change(function() {
        var assessment_type = $(this).val();
        $.ajax({
            url: assessment_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_assessment_fields',
                assessment_type: assessment_type
            },
            success: function(response) {
                if(response.success) {
                    $('#assessment-fields').html(response.data);
                } else {
                    alert(response.data);
                }
            }
        });
    });

    $('#submit-assessment').click(function() {
        var assessment_data = {};
        $('#assessment-fields').find('input').each(function() {
            var exercise = $(this).attr('name');
            var result = $(this).val();
            assessment_data[exercise] = result;
        });

        $.ajax({
            url: assessment_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'submit_assessment',
                assessment_type: $('#assessment-type').val(),
                assessment_data: assessment_data
            },
            success: function(response) {
                if(response.success) {
                    alert('Assessment submitted successfully.');
                } else {
                    alert('Failed to submit assessment.');
                }
            }
        });
    });
});
