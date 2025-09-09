<?php

session_start();
include 'init.php';

?>

<!-- Update Profile Header -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-2">Update Profile</h1>
            <p class="text-xl text-blue-100">Processing your profile changes</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id     = $_POST['userid'];
    $user     = $_POST['username'];
    $email     = $_POST['email'];
    $name     = $_POST['full_name'];

    if (!empty($_POST['newpassword'])) {
        $pass = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
    } else {
        $pass = $_POST['oldpassword'];
    }

    $formErrors = array();

    if (strlen($user) < 4) {
        $formErrors[] = 'Username must be at least <strong>4 characters</strong> long';
    }
    if (strlen($user) > 20) {
        $formErrors[] = 'Username cannot be more than <strong>20 characters</strong> long';
    }
    if (empty($user)) {
        $formErrors[] = 'Username cannot be <strong>empty</strong>';
    }
    if (empty($name)) {
        $formErrors[] = 'Full name cannot be <strong>empty</strong>';
    }
    if (empty($email)) {
        $formErrors[] = 'Email cannot be <strong>empty</strong>';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = 'Please enter a <strong>valid email address</strong>';
    }

    if (!empty($formErrors)) {
        echo '<div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">';
        echo '<div class="flex items-center mb-4">';
        echo '<i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>';
        echo '<h3 class="text-lg font-semibold text-red-800">Please fix the following errors:</h3>';
        echo '</div>';
        echo '<ul class="space-y-2">';
        foreach($formErrors as $error) {
            echo '<li class="text-red-700 flex items-center"><i class="fas fa-times-circle mr-2"></i>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<div class="mt-4">';
        echo '<a href="editProfil.php" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">';
        echo '<i class="fas fa-arrow-left mr-2"></i>Go Back';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }

    // Check If There's No Error Proceed The Update Operation
    if (empty($formErrors)) {

        $stmt2 = $con->prepare("SELECT * FROM users WHERE username = ? AND id != ?");
        $stmt2->execute(array($user, $id));
        $count = $stmt2->rowCount();

        if ($count == 1) {
            echo '<div class="bg-red-50 border border-red-200 rounded-xl p-6">';
            echo '<div class="flex items-center">';
            echo '<i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>';
            echo '<div>';
            echo '<h3 class="text-lg font-semibold text-red-800 mb-1">Username Already Exists</h3>';
            echo '<p class="text-red-700">Sorry, this username is already taken. Please choose a different one.</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="mt-4">';
            echo '<a href="editProfil.php" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">';
            echo '<i class="fas fa-arrow-left mr-2"></i>Go Back';
            echo '</a>';
            echo '</div>';
            echo '</div>';
        } else {
            $stmt = $con->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, password = ? WHERE id = ?");
            $stmt->execute(array($user, $email, $name, $pass, $id));

            echo '<div class="bg-green-50 border border-green-200 rounded-xl p-6">';
            echo '<div class="flex items-center">';
            echo '<i class="fas fa-check-circle text-green-600 mr-3"></i>';
            echo '<div>';
            echo '<h3 class="text-lg font-semibold text-green-800 mb-1">Profile Updated Successfully!</h3>';
            echo '<p class="text-green-700">Your profile information has been updated successfully.</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $seconds = 3;
            echo '<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4">';
            echo '<div class="flex items-center">';
            echo '<i class="fas fa-info-circle text-blue-600 mr-3"></i>';
            echo '<p class="text-blue-700">Redirecting to your profile in ' . $seconds . ' seconds...</p>';
            echo '</div>';
            echo '</div>';

            echo '<script>';
            echo 'let countdown = ' . $seconds . ';';
            echo 'const countdownElement = document.querySelector(".countdown");';
            echo 'const interval = setInterval(() => {';
            echo '  countdown--;';
            echo '  if (countdown <= 0) {';
            echo '    clearInterval(interval);';
            echo '    window.location.href = "profile.php";';
            echo '  }';
            echo '}, 1000);';
            echo 'setTimeout(() => window.location.href = "profile.php", ' . ($seconds * 1000) . ');';
            echo '</script>';
        }
    }

} else {
    echo '<div class="bg-red-50 border border-red-200 rounded-xl p-6">';
    echo '<div class="flex items-center">';
    echo '<i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>';
    echo '<div>';
    echo '<h3 class="text-lg font-semibold text-red-800 mb-1">Access Denied</h3>';
    echo '<p class="text-red-700">Sorry, you cannot access this page directly.</p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="mt-4">';
    echo '<a href="profile.php" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">';
    echo '<i class="fas fa-home mr-2"></i>Go to Profile';
    echo '</a>';
    echo '</div>';
    echo '</div>';
}

?>

    </div>
</div>
?>
<?php include $tpl . 'footer.php'; ?>