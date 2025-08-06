// Function to validate a field with a number-only regex
function validateNumberOnly(input, errorId) {
    const value = input.value.trim();
    const errorElement = document.getElementById(errorId);
    const regex = /^[0-9]+$/;
    if (!value) {
        errorElement.textContent = "This field is required.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else if (!regex.test(value)) {
        errorElement.textContent = "Only numbers are allowed.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else {
        errorElement.textContent = "";
        errorElement.style.display = "none";
        input.classList.add('valid');
        input.classList.remove('invalid');
        return true;
    }
}

// Function to validate a field with a text-only regex (letters, spaces, dots)
function validateTextOnly(input, errorId) {
    const value = input.value.trim();
    const errorElement = document.getElementById(errorId);
    const regex = /^[a-zA-Z\s.]+$/;
    if (!value) {
        errorElement.textContent = "This field is required.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else if (!regex.test(value)) {
        errorElement.textContent = "Only letters, spaces, and dots are allowed.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else {
        errorElement.textContent = "";
        errorElement.style.display = "none";
        input.classList.add('valid');
        input.classList.remove('invalid');
        return true;
    }
}

// Function for general mixed-content validation (not empty)
function validateMixed(input, errorId) {
    const value = input.value.trim();
    const errorElement = document.getElementById(errorId);
    if (value === '') {
        errorElement.textContent = "This field is required.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else {
        errorElement.textContent = "";
        errorElement.style.display = "none";
        input.classList.add('valid');
        input.classList.remove('invalid');
        return true;
    }
}

// Function to validate height field
function validateHeight(input, errorId) {
    const value = input.value.trim();
    const errorElement = document.getElementById(errorId);
    const regex = /^[A-Za-z0-9\s."'-]+$/; // Allows numbers, letters, spaces, and common height symbols
    if (value === '') {
        errorElement.textContent = "This field is required.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else if (!regex.test(value)) {
        errorElement.textContent = "Invalid height format.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else {
        errorElement.textContent = "";
        errorElement.style.display = "none";
        input.classList.add('valid');
        input.classList.remove('invalid');
        return true;
    }
}

// Function to validate a number range (e.g., '25-30')
function validateAgeRange(input, errorId) {
    const value = input.value.trim();
    const errorElement = document.getElementById(errorId);
    const regex = /^\d+-\d+$/;
    if (value === '') {
        errorElement.textContent = "This field is required.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else if (!regex.test(value)) {
        errorElement.textContent = "Please use the format 'min-max', e.g., 25-30.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else {
        errorElement.textContent = "";
        errorElement.style.display = "none";
        input.classList.add('valid');
        input.classList.remove('invalid');
        return true;
    }
}

// Function to validate a dropdown (select) field
function validateSelect(input, errorId) {
    const value = input.value;
    const errorElement = document.getElementById(errorId);
    if (!value || value === '') {
        errorElement.textContent = "Please select an option.";
        errorElement.style.display = "block";
        input.classList.add('invalid');
        input.classList.remove('valid');
        return false;
    } else {
        errorElement.textContent = "";
        errorElement.style.display = "none";
        input.classList.add('valid');
        input.classList.remove('invalid');
        return true;
    }
}

// Main form validation function
function validateForm(event) {
    event.preventDefault(); // Prevent default form submission

    let isValid = true;

    // List of fields to validate
    const fieldsToValidate = [
        { id: 'name', type: 'text', validator: validateTextOnly },
        { id: 'date_of_birth', type: 'text', validator: validateMixed },
        { id: 'place_of_birth', type: 'text', validator: validateTextOnly },
        { id: 'age', type: 'number', validator: validateNumberOnly },
        { id: 'height', type: 'text', validator: validateHeight },
        { id: 'marital_status', type: 'select', validator: validateSelect },
        { id: 'religion', type: 'text', validator: validateTextOnly },
        { id: 'nationality', type: 'text', validator: validateTextOnly },
        { id: 'contact_number', type: 'text', validator: validateMixed },
        { id: 'email', type: 'email', validator: validateMixed },
        { id: 'father_name', type: 'text', validator: validateTextOnly },
        { id: 'mother_name', type: 'text', validator: validateTextOnly },
        { id: 'siblings', type: 'number', validator: validateNumberOnly },
        { id: 'family_financial_status', type: 'text', validator: validateMixed },
        { id: 'family_religion', type: 'text', validator: validateTextOnly },
        { id: 'partner_age_range', type: 'text', validator: validateAgeRange },
        { id: 'partner_height', type: 'text', validator: validateHeight },
        { id: 'partner_marital_status', type: 'select', validator: validateSelect },
        { id: 'partner_religion', type: 'text', validator: validateTextOnly },
        { id: 'partner_occupation', type: 'text', validator: validateTextOnly },
        { id: 'about_me', type: 'textarea', validator: validateMixed },
        { id: 'future_plans', type: 'textarea', validator: validateMixed },
        { id: 'health_issues', type: 'text', validator: validateMixed },
        { id: 'languages', type: 'textarea', validator: validateMixed },
        { id: 'preferred_location', type: 'textarea', validator: validateMixed },
        { id: 'social_media', type: 'textarea', validator: validateMixed }
    ];

    fieldsToValidate.forEach(field => {
        const inputElement = document.getElementById(field.id);
        const errorElementId = field.id + '-error';
        if (!field.validator(inputElement, errorElementId)) {
            isValid = false;
        }
    });

    // Specific validation for photo upload
    const photoInput = document.getElementById('photo');
    const photoError = document.getElementById('photo-error');
    if (!photoInput.value) {
        photoError.textContent = "Photo is required.";
        photoError.style.display = "block";
        isValid = false;
    } else {
        const file = photoInput.files[0];
        const allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        const fileExt = file.name.split('.').pop().toLowerCase();
        if (!allowedExts.includes(fileExt)) {
            photoError.textContent = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            photoError.style.display = "block";
            isValid = false;
        } else {
            photoError.textContent = "";
            photoError.style.display = "none";
        }
    }

    // Specific validation for gender radio buttons
    const genderMale = document.getElementById('gender_male');
    const genderFemale = document.getElementById('gender_female');
    const genderError = document.getElementById('gender-error');
    if (!genderMale.checked && !genderFemale.checked) {
        genderError.textContent = "Gender is required.";
        genderError.style.display = "block";
        isValid = false;
    } else {
        genderError.textContent = "";
        genderError.style.display = "none";
    }

    if (isValid) {
        // If all validations pass, submit the form
        document.getElementById('biodataForm').submit();
    }
}

// Add event listener to the form to trigger validation on submission
document.getElementById('biodataForm').addEventListener('submit', validateForm);
