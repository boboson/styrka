jQuery(document).ready(function($) {
    $('#assessment-type').change(function() {
        var assessmentType = $(this).val();
        console.log('Selected assessment type:', assessmentType);
        if (assessmentType) {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'get_assessment_fields',
                    type: assessmentType,
                },
                success: function(response) {
                    console.log('AJAX response:', response);
                    if (response.success) {
                        $('#assessment-fields').html(response.data.html);
                    } else {
                        alert(response.data.message || 'Failed to load assessment fields.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    alert('An error occurred while fetching the assessment fields.');
                }
            });
        }
    });

    $('#submit-assessment').click(function(e) {
        e.preventDefault();
        var assessmentType = $('#assessment-type').val();
        var results = $('#assessment-form').serialize();
        
        console.log('Submitting assessment with type:', assessmentType);
        console.log('Assessment results:', results);
        
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'save_assessment_data',
                assessment_type: assessmentType,
                results: results,
            },
            success: function(response) {
                console.log('Save AJAX response:', response);
                if (response.success) {
                    alert('Assessment data saved successfully.');
                } else {
                    alert(response.data.message || 'Failed to save assessment data.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Save AJAX error:', textStatus, errorThrown);
                alert('An error occurred while saving the assessment data.');
            }
        });
    });
});
