<?php
	session_start();
	$noNavbar = '';
	$pageTitle = 'Login';

	if (isset($_SESSION['Username'])) {
		header('Location: dashboard.php'); // Redirect To Dashboard Page
	}

	include 'init.php';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$username = $_POST['user'];
		$password = $_POST['pass'];
		// Check If The User Exist In Database
		$stmt = $con->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = 'admin' LIMIT 1");
		$stmt->execute(array($username));
		$row = $stmt->fetch();
		if ($row && password_verify($password, $row['password'])) {
			$_SESSION['Username'] = $username; // Register Session Name
			$_SESSION['ID'] = $row['id']; // Register Session ID
			header('Location: dashboard.php'); // Redirect To Dashboard Page
			exit();
		}

	}

?>

<!-- Admin Login Page with Modern Design -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login - eCommerce Store</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 min-h-screen flex items-center justify-center p-4">
	<!-- Background Pattern -->
	<div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20"></div>
	<div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-900/20 via-transparent to-purple-900/20"></div>
	
	<!-- Login Container -->
	<div class="relative z-10 w-full max-w-md mx-auto">
		<!-- Admin Login Card -->
		<div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 mx-auto">
			<!-- Header -->
			<div class="text-center mb-8">
				<div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
					<i class="fas fa-shield-alt text-4xl text-white"></i>
				</div>
				<h1 class="text-4xl font-bold text-white mb-3 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">Admin Portal</h1>
				<p class="text-blue-200 text-lg">Secure access to dashboard</p>
			</div>

			<!-- Login Form -->
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="space-y-6">
				<!-- Username Field -->
				<div>
					<label class="block text-white text-sm font-medium mb-3">
						<i class="fas fa-user mr-2 text-blue-300"></i>Administrator Username
					</label>
					<input 
						type="text" 
						name="user" 
						class="w-full px-5 py-4 bg-white/15 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400/50 transition-all duration-300"
						placeholder="Enter admin username" 
						autocomplete="off" 
						required />
				</div>

				<!-- Password Field -->
				<div>
					<label class="block text-white text-sm font-medium mb-3">
						<i class="fas fa-lock mr-2 text-blue-300"></i>Administrator Password
					</label>
					<input 
						type="password" 
						name="pass" 
						class="w-full px-5 py-4 bg-white/15 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400/50 transition-all duration-300"
						placeholder="Enter admin password" 
						autocomplete="new-password" 
						required />
				</div>

				<!-- Login Button -->
				<button 
					type="submit" 
					class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/30 shadow-lg">
					<i class="fas fa-sign-in-alt mr-3"></i>Access Admin Panel
				</button>
			</form>

			<!-- Security Notice -->
			<div class="mt-6 bg-yellow-500/20 border border-yellow-400/30 rounded-lg p-4">
				<div class="flex products-start">
					<i class="fas fa-shield-alt text-yellow-400 mt-1 mr-3"></i>
					<div>
						<h4 class="text-sm font-semibold text-yellow-200 mb-1">Secure Access</h4>
						<p class="text-sm text-yellow-300">This area is restricted to authorized administrators only.</p>
					</div>
				</div>
			</div>

			<!-- Back to Site Link -->
			<div class="mt-6 text-center">
				<a href="../index.php" class="text-blue-300 hover:text-blue-200 transition-colors text-sm">
					<i class="fas fa-arrow-left mr-1"></i>Back to Main Site
				</a>
			</div>
		</div>

		<!-- Footer -->
		<div class="text-center mt-6">
			<p class="text-white/60 text-sm">&copy; 2025 eCommerce Store Admin Panel</p>
		</div>
	</div>

	<!-- Login Error Display -->
	<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['Username'])): ?>
	<div class="fixed top-4 right-4 z-50">
		<div class="bg-red-500/20 backdrop-blur-sm border border-red-400/30 text-white px-6 py-4 rounded-lg shadow-lg">
			<div class="flex items-center">
				<i class="fas fa-exclamation-triangle mr-3 text-red-400"></i>
				<div>
					<p class="font-semibold">Login Failed</p>
					<p class="text-sm text-red-200">Invalid admin credentials. Please try again.</p>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		// Auto-hide error message
		setTimeout(function() {
			const errorDiv = document.querySelector('.fixed.top-4.right-4');
			if (errorDiv) {
				errorDiv.style.transition = 'opacity 0.5s';
				errorDiv.style.opacity = '0';
				setTimeout(() => errorDiv.remove(), 500);
			}
		}, 4000);
	</script>
	<?php endif; ?>

</body>
</html>