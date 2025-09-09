document.addEventListener('DOMContentLoaded', function() {
	const loginTab = document.getElementById('loginTab');
	const signupTab = document.getElementById('signupTab');
	const loginForm = document.getElementById('loginForm');
	const signupForm = document.getElementById('signupForm');
	const formDescription = document.getElementById('formDescription');
	const showSignup = document.getElementById('showSignup');
	const showLogin = document.getElementById('showLogin');

	// Function to show login form
	function showLoginForm() {
		loginForm.classList.remove('hidden');
		signupForm.classList.add('hidden');
		loginTab.classList.add('border-white');
		loginTab.classList.remove('border-transparent');
		signupTab.classList.add('border-transparent');
		signupTab.classList.remove('border-white');
		formDescription.textContent = 'Access your account';
	}

	// Function to show signup form
	function showSignupForm() {
		signupForm.classList.remove('hidden');
		loginForm.classList.add('hidden');
		signupTab.classList.add('border-white');
		signupTab.classList.remove('border-transparent');
		loginTab.classList.add('border-transparent');
		loginTab.classList.remove('border-white');
		formDescription.textContent = 'Create a new account';
	}

	// Event listeners
	loginTab.addEventListener('click', showLoginForm);
	signupTab.addEventListener('click', showSignupForm);
	showSignup.addEventListener('click', showSignupForm);
	showLogin.addEventListener('click', showLoginForm);

	// Show login form by default
	showLoginForm();
});
