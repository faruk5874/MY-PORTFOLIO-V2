document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registrationForm');

  const emailInput = document.getElementById('email');
  const emailCodeInput = document.getElementById('emailCode');
  const sendEmailCodeBtn = document.getElementById('sendEmailCode');

  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirmPassword');

  const phoneInput = document.getElementById('phone');
  const smsCodeInput = document.getElementById('smsCode');
  const sendSmsCodeBtn = document.getElementById('sendSmsCode');

  const agreement1 = document.getElementById('agreement1');
  const agreement2 = document.getElementById('agreement2');

  const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?{}[\]~])[A-Za-z\d!@#$%^&*()_\-+=<>?{}[\]~]{6,20}$/;
  const phoneRegex = /^\d{6,15}$/;

  function showError(input, message) {
    clearError(input);
    const error = document.createElement('div');
    error.className = 'error-message';
    error.style.color = 'red';
    error.textContent = message;
    input.parentNode.insertBefore(error, input.nextSibling);
  }

  function clearError(input) {
    const next = input.nextSibling;
    if (next && next.classList && next.classList.contains('error-message')) {
      next.remove();
    }
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validatePhone(phone) {
    return phoneRegex.test(phone);
  }

  function validateEmailInput() {
    const email = emailInput.value.trim();
    clearError(emailInput);
    if (!email) {
      showError(emailInput, 'Email is required.');
      return false;
    }
    if (!validateEmail(email)) {
      showError(emailInput, 'Please enter a valid email address.');
      return false;
    }
    return true;
  }

  function validateEmailCodeInput() {
    const emailCode = emailCodeInput.value.trim();
    clearError(emailCodeInput);
    if (!emailCode) {
      showError(emailCodeInput, 'Email verification code is required.');
      return false;
    }
    return true;
  }

  function validatePasswordInput() {
    const password = passwordInput.value;
    clearError(passwordInput);
    if (!password) {
      showError(passwordInput, 'Password is required.');
      return false;
    }
    if (!passwordRegex.test(password)) {
      showError(passwordInput, 'Password must be 6-20 chars, including letters, digits, and special symbols.');
      return false;
    }
    return true;
  }

  function validateConfirmPasswordInput() {
    const confirmPassword = confirmPasswordInput.value;
    clearError(confirmPasswordInput);
    if (!confirmPassword) {
      showError(confirmPasswordInput, 'Please confirm your password.');
      return false;
    }
    if (confirmPassword !== passwordInput.value) {
      showError(confirmPasswordInput, 'Passwords do not match.');
      return false;
    }
    return true;
  }

  function validatePhoneInput() {
    const phone = phoneInput.value.trim();
    clearError(phoneInput);
    if (!phone) {
      showError(phoneInput, 'Phone number is required.');
      return false;
    }
    if (!validatePhone(phone)) {
      showError(phoneInput, 'Please enter a valid phone number.');
      return false;
    }
    return true;
  }

  function validateSmsCodeInput() {
    const smsCode = smsCodeInput.value.trim();
    clearError(smsCodeInput);
    if (!smsCode) {
      showError(smsCodeInput, 'SMS verification code is required.');
      return false;
    }
    return true;
  }

  function validateAgreements() {
    clearError(agreement1);
    clearError(agreement2);
    let valid = true;
    if (!agreement1.checked) {
      showError(agreement1, 'You must agree to the Cross-border Merchants Unified Workbench Service Agreement.');
      valid = false;
    }
    if (!agreement2.checked) {
      showError(agreement2, 'You must agree to the Consent Letter for Cross-border Transmission of Personal Information.');
      valid = false;
    }
    return valid;
  }

  // Event listeners for real-time validation on blur/input
  emailInput.addEventListener('blur', validateEmailInput);
  emailInput.addEventListener('input', validateEmailInput);

  emailCodeInput.addEventListener('blur', validateEmailCodeInput);
  emailCodeInput.addEventListener('input', validateEmailCodeInput);

  passwordInput.addEventListener('blur', validatePasswordInput);
  passwordInput.addEventListener('input', validatePasswordInput);

  confirmPasswordInput.addEventListener('blur', validateConfirmPasswordInput);
  confirmPasswordInput.addEventListener('input', validateConfirmPasswordInput);

  phoneInput.addEventListener('blur', validatePhoneInput);
  phoneInput.addEventListener('input', validatePhoneInput);

  smsCodeInput.addEventListener('blur', validateSmsCodeInput);
  smsCodeInput.addEventListener('input', validateSmsCodeInput);

  agreement1.addEventListener('change', validateAgreements);
  agreement2.addEventListener('change', validateAgreements);

  // Password toggle buttons
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', () => {
      const targetId = button.getAttribute('data-target');
      const targetInput = document.getElementById(targetId);
      if (targetInput.type === 'password') {
        targetInput.type = 'text';
        button.textContent = 'Hide';
      } else {
        targetInput.type = 'password';
        button.textContent = 'Show';
      }
    });
  });

  sendEmailCodeBtn.addEventListener('click', () => {
    if (validateEmailInput()) {
      alert(`Verification code sent to ${emailInput.value.trim()} (stub)`);
      // Real sending logic here
    }
  });

  sendSmsCodeBtn.addEventListener('click', () => {
    if (validatePhoneInput()) {
      alert(`SMS code sent to +86 ${phoneInput.value.trim()} (stub)`);
      // Real sending logic here
    }
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    let valid =
      validateEmailInput() &&
      validateEmailCodeInput() &&
      validatePasswordInput() &&
      validateConfirmPasswordInput() &&
      validatePhoneInput() &&
      validateSmsCodeInput() &&
      validateAgreements();

    if (valid) {
      alert('Form submitted successfully!');
      form.submit();
    } else {
      alert('Please fix the errors in the form.');
    }
  });
});
document.addEventListener('DOMContentLoaded', () => {
  const translations = {
    en: {
      page_title: "Welcome to the Cross-Border Seller Center",
      form_title: "Fill in the registration information",
      label_country: "Country/Region of Company Registration",
      note_country: "Once registered, the country/region cannot be changed. Please choose carefully.",
      email_label: "Please enter your email",
      email_placeholder: "Please enter your email",
      email_code: "Please enter email verification code",
      send: "Send",
      password_label: "Set up an account password",
      password_note: "The password needs to be a combination of 6-20 digits, letters, and special symbols.",
      password_placeholder: "Please enter your password",
      confirm_password: "Please enter password again.",
      phone_label: "Bind Phone Number",
      phone_placeholder: "Please enter your mobile number",
      sms_placeholder: "Please enter the SMS verification code.",
      agreement1: "I have read and agree to the",
      agreement2: "I have read and agree to the",
      submit: "Submit"
    },
    bn: {
      page_title: "ক্রস-বর্ডার সেলার সেন্টারে আপনাকে স্বাগতম",
      form_title: "নিবন্ধনের তথ্য পূরণ করুন",
      label_country: "কোম্পানি নিবন্ধনের দেশ/অঞ্চল",
      note_country: "একবার নিবন্ধিত হলে, দেশ/অঞ্চল পরিবর্তন করা যাবে না। দয়া করে সাবধানে নির্বাচন করুন।",
      email_label: "আপনার ইমেইল লিখুন",
      email_placeholder: "আপনার ইমেইল লিখুন",
      email_code: "ইমেইল যাচাইকরণ কোড লিখুন।",
      send: "পাঠান",
      password_label: "একটি পাসওয়ার্ড সেট করুন",
      password_placeholder: "আপনার পাসওয়ার্ড লিখুন",  
      password_note: "পাসওয়ার্ডে ৬-২০ অক্ষরের ডিজিট, বর্ণ এবং চিহ্ন থাকতে হবে।",
      confirm_password: "পাসওয়ার্ড আবার লিখুন।",
      phone_label: "মোবাইল নম্বর সংযুক্ত করুন",
      phone_placeholder: "আপনার মোবাইল নম্বর লিখুন",
      sms_placeholder: "এসএমএস যাচাইকরণ কোড লিখুন।",
      agreement1: "আমি পড়েছি এবং সম্মত",
      agreement2: "আমি পড়েছি এবং সম্মত",
      submit: "জমা দিন"
    }
  };

  const langSelect = document.getElementById('languageToggle');

  if (langSelect) {
    langSelect.addEventListener('change', () => {
      const lang = langSelect.value;
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (!translations[lang][key]) return;
        if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
          el.placeholder = translations[lang][key];
        } else {
          el.textContent = translations[lang][key];
        }
      });

      // Buttons and extra labels
      const btnSendList = ['sendEmailCode', 'sendSmsCode'];
      btnSendList.forEach(id => {
        const btn = document.getElementById(id);
        if (btn) btn.textContent = translations[lang].send;
      });

      const submitBtn = document.querySelector('.submit-btn');
      if (submitBtn) submitBtn.textContent = translations[lang].submit;
    });
  }
});
