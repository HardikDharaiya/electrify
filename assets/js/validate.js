// assets/js/validate.js - "Electrify" Custom Declarative Validator
// Based on the excellent system provided by Hardik Dharaiya, with refinements.

$(document).ready(function () {
  
  function validateField(input) {
    let field = $(input);
    let value = field.val().trim();
    // Instead of a separate error span, we will find a sibling with the .invalid-feedback class.
    let errorContainer = field.siblings(".invalid-feedback");
    
    let validationTypes = (field.data("validation") || "").split(" ");
    let minLength = field.data("min") || 0;
    let maxLength = field.data("max") || 9999;
    
    let errorMessage = "";

    // Loop through each validation type for the field
    for (const type of validationTypes) {
        if (errorMessage) break; // Stop checking if an error is already found

        switch (type) {
            case "required":
                if (value === "") errorMessage = "This field is required.";
                break;

            case "alpha":
                if (!/^[A-Za-z\s]+$/.test(value)) errorMessage = "Only letters and spaces are allowed.";
                break;
                
            case "numeric":
                if (!/^\d+$/.test(value)) errorMessage = "Only numbers are allowed.";
                break;

            case "email":
                let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (value && !emailPattern.test(value)) errorMessage = "Please enter a valid email address.";
                break;

            case "strongPassword":
                // BUG FIX: Wrapped the regex test in a proper if statement.
                let pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (value && !pwdRegex.test(value)) {
                    errorMessage = "Password must include uppercase, lowercase, number, and special character.";
                }
                break;

            case "confirmPassword":
                let password = $("#" + field.data("password-id")).val();
                if (value !== password) errorMessage = "Passwords do not match.";
                break;

            case "min":
                if (value.length < minLength) errorMessage = `Must be at least ${minLength} characters.`;
                break;

            case "max":
                if (value.length > maxLength) errorMessage = `Must be less than ${maxLength} characters.`;
                break;
        }
    }

    if (errorMessage) {
        errorContainer.text(errorMessage).show();
        field.addClass("is-invalid").removeClass("is-valid");
        return false;
    } else {
        errorContainer.hide().text("");
        field.removeClass("is-invalid").addClass("is-valid");
        return true;
    }
  }

  // Validate on blur (when user clicks away from a field)
  $("input[data-validation], textarea[data-validation]").on("blur", function () {
    validateField(this);
  });
  
  // Validate on form submission
  $("form").on("submit", function (e) {
    let isFormValid = true;
    // Find all fields in the current form that have a validation attribute
    $(this).find("input[data-validation], textarea[data-validation]").each(function () {
      // If any field is invalid, set the form's validity to false
      if (!validateField(this)) {
        isFormValid = false;
      }
    });

    // BUG FIX: Prevent submission if the form is not valid
    if (!isFormValid) {
      e.preventDefault();
    }
  });
});