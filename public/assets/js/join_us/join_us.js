const FIELD_CONFIG = [
  { id: 'first-name', required: true, msg: 'First name is required' },
  { id: 'last-name', required: true, msg: 'Last name is required' },
  // { id: 'middle-name', required: false }, // Example of optional field
  { id: 'date-of-birth', required: true, msg: 'Date of birth is required' },
  { id: 'civil-status', required: true, msg: 'Civil status is required' },
  { id: 'blood-type', required: true, msg: 'Blood type is required' },
  { id: 'home-address', required: true, msg: 'Home address is required' },
  { id: 'phone-number', required: true, msg: 'Phone number is required' },
  { id: 'email', required: true, msg: 'Email is required' },
  {
    id: 'emergency-contact-name',
    required: true,
    msg: 'Contact person is required',
  },
  {
    id: 'emergency-contact-number',
    required: true,
    msg: 'Emergency contact number is required',
  },
  { id: 'occupation', required: true, msg: 'Occupation is required' },
  { id: 'license-number', required: true, msg: 'License number is required' },
  {
    id: 'motorcycle-brand',
    required: true,
    msg: 'Motorcycle brand is required',
  },
  {
    id: 'motorcycle-model',
    required: true,
    msg: 'Motorcycle model is required',
  },
  { id: 'chapter-id', required: true, msg: 'Chapter is required' },
  { id: 'date-joined', required: true, msg: 'Date joined is required' },
];

const CHECKBOX_CONFIG = [
  {
    id: 'terms-privacy-consent',
    msg: 'You must agree to the Terms of Service and Privacy Policy',
  },
  { id: 'liability-waiver', msg: 'You must acknowledge the liability waiver' },
];

const membershipForm = document.getElementById('membership-form');
const submitBtn = document.getElementById('submit-btn');

membershipForm.addEventListener('input', handleFieldInteraction);
membershipForm.addEventListener('change', handleFieldInteraction);
membershipForm.addEventListener('submit', handleFormSubmit);

async function handleFormSubmit(e) {
  e.preventDefault();

  submitBtn.disabled = true;

  const isFormValid = validateAllFields();

  if (!isFormValid) {
    displayToast('', 'Please fill all required fields correctly', 'warning');
    submitBtn.disabled = false;
    return;
  }

  const formData = new FormData(membershipForm);
  const payload = Object.fromEntries(formData);

  toggleLoading(true);

  try {
    const response = await fetch('../api/backend.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Server error occured');
    }

    if (result.status !== 'success') {
      displayToast('', result.message, result.status);
      toggleLoading(false);
      submitBtn.disabled = false;
      return;
    }

    displayToast(
      '',
      result.message || 'Application successfully submitted',
      result.status
    );
  } catch (error) {
    console.error('Submission Error:', error);
    displayToast(
      'Error',
      'Unable to connect to the server. Please try again',
      'error'
    );

    toggleLoading(false);
    submitBtn.disabled = false;
  }
}

function validateAllFields() {
  let isValid = true;

  FIELD_CONFIG.forEach((field) => {
    if (!field.required) return;

    const input = document.getElementById(field.id);
    const value = input.value.trim();

    if (!toggleErrorState(input, value === '', field.msg)) {
      isValid = false;
    }
  });

  CHECKBOX_CONFIG.forEach((box) => {
    const checkbox = document.getElementById(box.id);
    const isChecked = checkbox.checked;

    if (!toggleCheckboxError(checkbox, !isChecked, box.msg)) {
      isValid = false;
    }
  });

  return isValid;
}

function toggleErrorState(element, isError, message) {
  const feedback = element.nextElementSibling;

  if (isError) {
    element.classList.add('is-invalid');
    element.classList.remove('is-valid');

    if (feedback) {
      feedback.textContent = message;
      feedback.classList.add('d-block');
    }
    return false;
  } else {
    element.classList.remove('is-invalid');
    element.classList.add('is-valid');
    if (feedback) feedback.classList.remove('d-block');
    return true;
  }
}

function toggleCheckboxError(element, isError, message) {
  const formCheck = element.closest('.form-check');
  const feedback = formCheck.querySelector('.invalid-feedback');

  if (isError) {
    if (feedback) {
      feedback.textContent = message;
      feedback.classList.add('d-block');
    }
    return false;
  } else {
    if (feedback) feedback.classList.remove('d-block');
    return true;
  }
}

function toggleLoading(isActive) {
  const pageOverlay = document.getElementById('page-overlay');
  const formElements = document.querySelectorAll('input, select, button');

  if (pageOverlay) pageOverlay.classList.toggle('d-none', !isActive);
  formElements.forEach((element) => (element.disabled = isActive));
}

function displayToast(title, message, type) {
  if (typeof iziToast === 'undefined') return alert(message);

  iziToast[type]({
    title: title,
    message: message,
    position: 'topCenter',
    timeout: type === 'success' ? 0 : 3000,
    onClosed: () => {
      if (type === 'success') location.reload();
    },
  });
}

function handleFieldInteraction(e) {
  const target = e.target;

  if (target.matches('.form-control, .form-select')) {
    target.classList.remove('is-invalid', 'is-valid');
    const feedback = target.nextElementSibling;
    if (feedback) feedback.classList.remove('d-block');
  }

  if (target.matches('.form-check-input')) {
    const formCheck = target.closest('.form-check');
    const feedback = formCheck?.querySelector('.invalid-feedback');
    if (feedback) feedback.classList.remove('d-block');
  }
}
