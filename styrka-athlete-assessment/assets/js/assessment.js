jQuery(document).ready(function($) {
    $('#assessment-type').change(function() {
        var assessmentType = $(this).val();
        var data = {
            action: 'get_assessment_fields',
            assessment_type: assessmentType,
            security: $('#styrka_assessment_nonce_field').val()
        };

        $.post(ajaxurl, data, function(response) {
            if (response.success) {
                $('#assessment-fields').html(response.data.html);
            } else {
                alert(response.data.message);
            }
        });
    });

    $('#styrka-assessment-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.post(ajaxurl, formData, function(response) {
            if (response.success) {
                alert(response.data.message);
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });
});
