function validateNumberOnly(input) {
    const value = input.value;
    const numberRegex = /^\d*$/;
    input.classList.remove('valid', 'invalid');
    if (value === '') {
        return;
    }
    if (numberRegex.test(value)) {
        input.classList.add('valid');
    } else {
        input.classList.add('invalid');
    }
}

function validateTextOnly(input) {
    const value = input.value;
    const textRegex = /^[A-Za-z\s.]*$/;
    input.classList.remove('valid', 'invalid');
    if (value === '') {
        return;
    }
    if (textRegex.test(value)) {
        input.classList.add('valid');
    } else {
        input.classList.add('invalid');
    }
}

function validateMixed(input) {
    const value = input.value;
    input.classList.remove('valid', 'invalid');
    if (value === '') {
        input.classList.add('invalid');
    } else {
        input.classList.add('valid');
    }
}

function validateHeight(input) {
    const value = input.value;
    const heightRegex = /^[A-Za-z0-9\s."]*$/;
    input.classList.remove('valid', 'invalid');
    if (value === '') {
        return;
    }
    if (heightRegex.test(value)) {
        input.classList.add('valid');
    } else {
        input.classList.add('invalid');
    }
}

function validateAgeRange(input) {
    const value = input.value;
    const ageRangeRegex = /^\d*-\d*$/;
    input.classList.remove('valid', 'invalid');
    if (value === '') {
        return;
    }
    if (ageRangeRegex.test(value)) {
        input.classList.add('valid');
    } else {
        input.classList.add('invalid');
    }
}

function validateForm(event) {
    event.preventDefault();
    let isValid = true;

    const numberFields = [
        { id: 'age', element: document.getElementById('age') },
        { id: 'contact', element: document.getElementById('contact') },
        { id: 'income', element: document.getElementById('income') },
        { id: 'passingYear', element: document.querySelector('input[name="passingYear"]') }
    ];
    const numberRegex = /^\d+$/;

    numberFields.forEach(field => {
        const value = field.element.value.trim();
        if (value === '' || !numberRegex.test(value)) {
            field.element.classList.add('invalid');
            field.element.classList.remove('valid');
            isValid = false;
        } else {
            field.element.classList.add('valid');
            field.element.classList.remove('invalid');
        }
    });

    const textFields = [
        { id: 'name', element: document.getElementById('name') },
        { id: 'placeOfBirth', element: document.getElementById('placeOfBirth') },
        { id: 'maritalStatus', element: document.getElementById('maritalStatus') },
        { id: 'religion', element: document.getElementById('religion') },
        { id: 'nationality', element: document.getElementById('nationality') },
        { id: 'fatherName', element: document.getElementById('fatherName') },
        { id: 'fatherOccupation', element: document.getElementById('fatherOccupation') },
        { id: 'motherName', element: document.getElementById('motherName') },
        { id: 'motherOccupation', element: document.getElementById('motherOccupation') },
        { id: 'occupation', element: document.getElementById('occupation') },
        { id: 'currentEducation', element: document.getElementById('currentEducation') },
        { id: 'certifications', element: document.getElementById('certifications') },
        { id: 'careerPlan', element: document.getElementById('careerPlan') },
        { id: 'complexion', element: document.getElementById('complexion') },
        { id: 'bodyType', element: document.getElementById('bodyType') },
        { id: 'diet', element: document.getElementById('diet') },
        { id: 'hobbies', element: document.getElementById('hobbies') },
        { id: 'partnerEducation', element: document.getElementById('partnerEducation') },
        { id: 'partnerOccupation', element: document.getElementById('partnerOccupation') },
        { id: 'partnerReligion', element: document.getElementById('partnerReligion') },
        { id: 'aboutMe', element: document.getElementById('aboutMe') },
        { id: 'futurePlans', element: document.getElementById('futurePlans') },
        { id: 'healthIssues', element: document.getElementById('healthIssues') },
        { id: 'languages', element: document.getElementById('languages') },
        { id: 'location', element: document.getElementById('location') },
        { id: 'qualification', element: document.querySelector('input[name="qualification"]') },
        { id: 'institute', element: document.querySelector('input[name="institute"]') }
    ];
    const textRegex = /^[A-Za-z\s.]+$/;

    textFields.forEach(field => {
        const value = field.element.value.trim();
        if (value === '' || !textRegex.test(value)) {
            field.element.classList.add('invalid');
            field.element.classList.remove('valid');
            isValid = false;
        } else {
            field.element.classList.add('valid');
            field.element.classList.remove('invalid');
        }
    });

    const heightFields = [
        { id: 'height', element: document.getElementById('height') },
        { id: 'partnerHeight', element: document.getElementById('partnerHeight') }
    ];
    const heightRegex = /^[A-Za-z0-9\s."]+$/;

    heightFields.forEach(field => {
        const value = field.element.value.trim();
        if (value === '' || !heightRegex.test(value)) {
            field.element.classList.add('invalid');
            field.element.classList.remove('valid');
            isValid = false;
        } else {
            field.element.classList.add('valid');
            field.element.classList.remove('invalid');
        }
    });

    const ageRangeField = document.getElementById('partnerAge');
    const ageRangeValue = ageRangeField.value.trim();
    const ageRangeRegex = /^\d+-\d+$/;
    if (ageRangeValue === '' || !ageRangeRegex.test(ageRangeValue)) {
        ageRangeField.classList.add('invalid');
        ageRangeField.classList.remove('valid');
        isValid = false;
    } else {
        ageRangeField.classList.add('valid');
        ageRangeField.classList.remove('invalid');
    }

    const mixedFields = [
        { id: 'email', element: document.getElementById('email') },
        { id: 'permanentAddress', element: document.getElementById('permanentAddress') },
        { id: 'presentAddress', element: document.getElementById('presentAddress') },
        { id: 'siblings', element: document.getElementById('siblings') },
        { id: 'socialMedia', element: document.getElementById('socialMedia') }
    ];

    mixedFields.forEach(field => {
        const value = field.element.value.trim();
        if (value === '') {
            field.element.classList.add('invalid');
            field.element.classList.remove('valid');
            isValid = false;
        } else {
            field.element.classList.add('valid');
            field.element.classList.remove('invalid');
        }
    });

    if (isValid) {
        alert('Form submitted successfully!');
    } else {
        alert('Please correct the errors in the form.');
    }

    return isValid;
}