<?php

    session_start();
    include 'init.php';

// Get the User ID from Session
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
function getSingleValue($con, $sql, $parameters) {
    $q = $con->prepare($sql);
    $q->execute($parameters);
    return $q->fetchColumn();
}

$userid = getSingleValue($con, "SELECT id FROM users WHERE username=?", [$_SESSION['user']]);
$stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->execute(array($userid));
$row = $stmt->fetch();
$count = $stmt->rowCount();
if ($count > 0) { ?>

<!-- Edit Profile Header -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 mx-auto px-4">
        <div class="text-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-user-edit text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Edit My Profile</h1>
            <p class="text-xl text-blue-100">Update your personal information</p>
        </div>
    </div>
</div>

<!-- Edit Profile Form -->
<div class="max-w-7xl mx-auto px-4 mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-3 text-blue-600"></i>
                    Personal Information
                </h2>
            </div>
            <div class="p-6">
                <form action="UpdateProfile.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-500"></i>Username
                        </label>
                        <input type="text"
                               id="username"
                               name="username"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?php echo htmlspecialchars($row['username']) ?>"
                               autocomplete="off" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="newpassword" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>New Password
                        </label>
                        <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" />
                        <input type="password"
                               id="newpassword"
                               name="newpassword"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               autocomplete="new-password"
                               placeholder="Leave blank if you don't want to change" />
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current password</p>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?php echo htmlspecialchars($row['email']) ?>" />
                    </div>

                    <!-- Full Name Field -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-gray-500"></i>Full Name
                        </label>
                        <input type="text"
                               id="full_name"
                               name="full_name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?php echo htmlspecialchars($row['full_name']); ?>" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="profile.php" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Profile
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex products-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-sm font-semibold text-blue-800 mb-1">Profile Tips</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Use a strong password with at least 8 characters</li>
                        <li>• Your email address is used for important notifications</li>
                        <li>• Complete your full name for a better experience</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include $tpl . 'footer.php'; ?>
<?php } 