<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php getTitle() ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#64748b'
                    }
                }
            }
        }
        </script>
        <style>
        .dropdown:hover .dropdown-menu { display: block; }
        .auto-hide { animation: fadeOut 3s forwards; }
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
        </style>
    </head>
    <body class="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen">
        
        <nav class="bg-gradient-to-r from-blue-600 via-blue-700 to-purple-700 shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="index.php" class="flex items-center space-x-2 text-white hover:text-blue-200 transition-colors">
                            <i class="fas fa-home text-xl"></i>
                            <span class="text-xl font-bold">eCommerce</span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <?php if (isset($_SESSION['user'])) { ?>
                            <div class="relative dropdown">
                                <button class="flex items-center space-x-3 text-white hover:text-blue-200 focus:outline-none">
                                    <img class="w-8 h-8 rounded-full border-2 border-white"
                                         src="admin/uploads/avatars/<?php echo $sessionAvatar ?>"
                                         alt="Profile">
                                    <span class="hidden md:block"><?php echo htmlspecialchars($sessionUser) ?></span>
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </button>
                                <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                                    <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Account
                                    </a>
                                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                                    </a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <a href="login.php" class="bg-gradient-to-r from-white/20 to-white/30 backdrop-blur-sm border border-white/30 text-white font-semibold px-6 py-2 rounded-full hover:from-white/30 hover:to-white/40 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>Sign in
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Container with Conditional Sidebar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?php
            
            $currentPage = basename($_SERVER['PHP_SELF']);
            $showSidebar = ($currentPage === 'index.php' || $currentPage === 'categories.php');
            ?>

            <?php if ($showSidebar): ?>
            <div class="flex flex-col-span-12 lg:flex-grid grid-cols-12 gap-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-list mr-2 text-blue-600"></i>
                            Categories
                        </h3>
                        <div class="space-y-2">
                            <?php
                            $allCats = getAllFrom("*", "categories", "id", "", "", "ASC");
                            foreach ($allCats as $cat) {
                                echo '<a href="categories.php?pageid=' . $cat['id'] . '"
                                    class="block px-3 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition-colors">
                                    ' . htmlspecialchars($cat['name']) . '
                                </a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
            <?php else: ?>
            <!-- Full width content for pages without sidebar -->
            <div class="w-full">
            <?php endif; ?>