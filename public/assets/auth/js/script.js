$('input').on('input', function() {
    let field = $(this);

    if (field.hasClass('is-invalid')) {
        field.removeClass('is-invalid'); // Remove red border

        // Remove the closest small element (error message)
        field.closest('.form-group').find('small').remove();
    }
});

$('input[name="terms"]').on('change', function() {
    let checkbox = $(this);

    if (checkbox.is(':checked')) {
        // Remove red border if added for validation
        checkbox.removeClass('is-invalid');

        // Hide the error message
        checkbox.siblings('small.error-message').fadeOut();
    }
});