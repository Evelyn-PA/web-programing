document.getElementById('registrationForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form submission

    // Get form values
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();

    // Reset JavaScript error messages
    const jsErrorMessages = document.getElementById('jsErrorMessages');
    jsErrorMessages.innerHTML = '';
    jsErrorMessages.style.display = 'none';

    // Validate username
    if (username === '') {
        showError('Username is required.');
        return;
    }

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError('Please enter a valid email address.');
        return;
    }

    // Validate password
    if (password.length < 6) {
        showError('Password must be at least 6 characters long.');
        return;
    }

    // Validate confirm password
    if (password !== confirmPassword) {
        showError('Passwords do not match.');
        return;
    }

    // If all validations pass, submit the form
    this.submit();
});

// Function to display error messages
function showError(message) {
    const jsErrorMessages = document.getElementById('jsErrorMessages');
    jsErrorMessages.innerHTML = `<p>${message}</p>`;
    jsErrorMessages.style.display = 'block';
}