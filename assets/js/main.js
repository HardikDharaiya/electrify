// assets/js/main.js - Custom JavaScript for Electrify (v1.1)

$(document).ready(function() {

    // -- REGISTRATION FORM VALIDATION --
    if ($("#registrationForm").length) {
        $("#registrationForm").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                // --- LOGIC CORRECTION ---
                // The minlength rule was removed from here. It's redundant and was causing
                // the incorrect error message to appear. The only rules needed are that the
                // field is required and that it must be equal to the main password field.
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
                email: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long"
                },
                // --- LOGIC CORRECTION ---
                // The minlength message was removed to match the corrected rules.
                confirm_password: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match. Please try again."
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                if (element.closest('.col-md-6').length) {
                    element.closest('.col-md-6').append(error);
                } else {
                    element.closest('.mb-3, .mb-4').append(error);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    }

});