<?php
    ob_start();
    session_start();
    $pageTitle = 'Profile';
    include 'init.php';

    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    $getUser = $con->prepare("SELECT * FROM users WHERE username = ?");
    $getUser->execute([$sessionUser]);
    $info = $getUser->fetch();
    if (!$info) {
        header('Location: login.php');
        exit();
    }

    $fullName    = $info['full_name'] ?: $info['username'];
    $memberSince = isset($info['created_at']) ? substr($info['created_at'], 0, 10) : '';
?>

<!-- Hero -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white">
  <div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold">Welcome back, <?php echo htmlspecialchars($fullName); ?>!</h1>
          <p class="text-white/80">Manage your account and preferences</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">

  <!-- Left: Personal Information -->
  <div class="md:col-span-2">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
      <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-user-circle text-blue-600"></i>
        <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
      </div>

      <div class="p-6 space-y-4">
        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center">
              <i class="fas fa-user"></i>
            </div>
            <div>
              <div class="text-xs text-gray-500">Username</div>
              <div class="font-medium text-gray-900"><?php echo htmlspecialchars($info['username']); ?></div>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <div class="text-xs text-gray-500">Email Address</div>
              <div class="font-medium text-gray-900"><?php echo htmlspecialchars($info['email']); ?></div>
            </div>
          </div>
        </div>

        <?php if (!empty($info['full_name'])) { ?>
        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center">
              <i class="fas fa-id-card"></i>
            </div>
            <div>
              <div class="text-xs text-gray-500">Full Name</div>
              <div class="font-medium text-gray-900"><?php echo htmlspecialchars($info['full_name']); ?></div>
            </div>
          </div>
        </div>
        <?php } ?>

        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center">
              <i class="fas fa-calendar"></i>
            </div>
            <div>
              <div class="text-xs text-gray-500">Member Since</div>
              <div class="font-medium text-gray-900"><?php echo htmlspecialchars($memberSince); ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Sidebar cards -->
  <div class="md:col-span-1 space-y-6">

    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
      <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-cog text-gray-600"></i>
        <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
      </div>
      <div class="p-6 space-y-3">
        <a href="editProfile.php"
           class="w-full inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-white font-semibold hover:bg-blue-700 transition">
          <i class="fas fa-pen mr-2"></i>Edit Profile
        </a>
        
      </div>
    </div>

    <!-- Security Tip -->
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 rounded-xl p-4">
      <div class="flex items-start gap-3">
        <i class="fas fa-shield-alt mt-1"></i>
        <div class="text-sm">
          <div class="font-semibold">Security Tip</div>
          <p>Keep your account secure by regularly updating your password and email.</p>
        </div>
      </div>
    </div>

  </div>
</div>

<?php
    include $tpl . 'footer.php';
    ob_end_flush();
?>