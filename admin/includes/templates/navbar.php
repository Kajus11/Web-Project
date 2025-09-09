<!-- Admin Dashboard Layout with Gradients -->
<div class="flex h-screen bg-gradient-to-br from-gray-100 to-blue-100">
  <!-- Sidebar with Gradient -->
  <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-800 via-gray-900 to-black transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-2xl">
    <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
      <h1 class="text-white text-xl font-bold bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
        <i class="fas fa-cogs mr-2 text-white"></i>
        Admin Panel
      </h1>
    </div>
    
    <nav class="mt-8">
      <div class="px-4 space-y-2">
        <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600/20 hover:to-purple-600/20 transition-all duration-200 rounded-lg transform hover:scale-105">
          <i class="fas fa-tachometer-alt mr-3 text-lg text-blue-400"></i>
          <span><?php echo lang('HOME_ADMIN') ?></span>
        </a>
        
        <a href="categories.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gradient-to-r hover:from-purple-600/20 hover:to-pink-600/20 transition-all duration-200 rounded-lg transform hover:scale-105">
          <i class="fas fa-tags mr-3 text-lg text-purple-400"></i>
          <span><?php echo lang('CATEGORIES') ?></span>
        </a>
        
        <a href="products.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gradient-to-r hover:from-green-600/20 hover:to-blue-600/20 transition-all duration-200 rounded-lg transform hover:scale-105">
          <i class="fas fa-box mr-3 text-lg text-green-400"></i>
          <span><?php echo lang('PRODUCTS') ?></span>
        </a>
        
        <a href="comments.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gradient-to-r hover:from-yellow-600/20 hover:to-orange-600/20 transition-all duration-200 rounded-lg transform hover:scale-105">
          <i class="fas fa-comments mr-3 text-lg text-yellow-400"></i>
          <span><?php echo lang('FEEDBACKS') ?></span>
        </a>
      </div>
    </nav>
    
    <!-- User Menu at Bottom -->
    <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
      <div class="relative">
        <button onclick="toggleDropdown()" class="flex items-center w-full px-4 py-2 text-gray-300 hover:text-white transition-colors duration-200 rounded-lg">
          <i class="fas fa-user-shield mr-3"></i>
          <span>Admin</span>
          <i class="fas fa-chevron-up ml-auto transform transition-transform duration-200" id="dropdown-icon"></i>
        </button>
        <div id="admin-dropdown" class="hidden absolute bottom-full left-0 right-0 mb-2 bg-gray-700 rounded-lg shadow-lg">
          <a href="../index.php" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-600 rounded-t-lg transition-colors duration-200">
            <i class="fas fa-store mr-2"></i>
            Visit Shop
          </a>
          <a href="logout.php" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-600 rounded-b-lg transition-colors duration-200">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="flex-1 lg:ml-0">
    <!-- Top Bar -->
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="flex items-center justify-between h-16 px-6">
        <div class="flex items-center">
          <button class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100" onclick="toggleSidebar()">
            <i class="fas fa-bars text-xl"></i>
          </button>
          <h2 class="ml-4 text-xl font-semibold text-gray-800"><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h2>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-gray-500">Welcome, Admin</span>
          <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
            <i class="fas fa-user text-white text-sm"></i>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <main class="p-6">
      <div class="max-w-7xl mx-auto">