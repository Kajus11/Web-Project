<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php getTitle() ?> - Admin Panel</title>
		<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
		<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						primary: '#1f2937',
						secondary: '#3b82f6',
						accent: '#10b981'
					}
				}
			}
		}
		</script>
		<style>
		.sidebar-link:hover { background: rgba(59, 130, 246, 0.1); }
		.auto-hide { animation: fadeOut 3s forwards; }
		@keyframes fadeOut { 
			0% { opacity: 1; } 
			70% { opacity: 1; } 
			100% { opacity: 0; } 
		}
		</style>
	</head>
	<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen">