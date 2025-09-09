<?php
    ob_start();
    session_start();
    $pageTitle = 'Login - eCommerce Store';
    if (isset($_SESSION['user'])) {
        header('Location: index.php');
    }
    include 'init.php';

    // Check If User Coming From HTTP Post Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            // Check If The User Exist In Database
            $stmt = $con->prepare("SELECT id, username, password, avatar, role FROM users WHERE username = ?");
            $stmt->execute(array($user));
            $get = $stmt->fetch();
            if ($get && password_verify($pass, $get['password'])) {
                $_SESSION['user'] = $get['username'];
                $_SESSION['uid'] = $get['id'];
                $_SESSION['avatar'] = $get['avatar'];
                $_SESSION['role'] = $get['role'];
                header('Location: index.php');
                exit();
            } else {
                $loginError = "Invalid username or password!";
            }
        } else {


            $formErrors = array();

            $username  = $_POST['username'];
            $password  = $_POST['password'];
            $password2 = $_POST['password2'];
            $email     = $_POST['email'];
            $fullname  = $_POST['fullname'];

               // Upload Variables
               $avatarName = isset($_FILES['pictures']['name']) ? $_FILES['pictures']['name'] : '';
               $avatarTmp  = isset($_FILES['pictures']['tmp_name']) ? $_FILES['pictures']['tmp_name'] : '';

               // List Of Allowed File Types To Upload
               $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
               $avatarExtension = '';
               if (!empty($avatarName)) {
                   $ref = explode('.', $avatarName);
                   $avatarExtension = strtolower(end($ref));
               }

               if (isset($username)) {
                   $filterdUser = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                   if (strlen($filterdUser) < 4) {
                       $formErrors[] = 'Username Must Be Larger Than 4 Characters';
                   }
               }
            if (isset($password) && isset($password2)) {
                if (empty($password)) {
                    $formErrors[] = 'Sorry Password Cant Be Empty';
                }
                if ($password !== $password2) {
                    $formErrors[] = 'Sorry Password Is Not Match';
                }
            }
            if (isset($email)) {
                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = 'This Email Is Not Valid';
                }
            }

            // Check If There's No Error Proceed The User Add
               if (empty($formErrors)) {
                   if (!empty($avatarName)) {
                       $avatar = rand(0, 10000000000) . '_' . $avatarName;
                       move_uploaded_file($avatarTmp, "admin/uploads/avatars/" . $avatar);
                   } else {
                       $avatar = 'default.png';
                   }
                   // Check If User Exist in Database
                   $check = checkItem("username", "users", $username);
                   if ($check == 1) {
                       $formErrors[] = 'Sorry This User Is Exists';
                   } else {
                       // Insert Userinfo In Database
                       $stmt = $con->prepare("INSERT INTO users(username, password, email, full_name, avatar, role) VALUES(:zuser, :zpass, :zmail, :zname, :zpic, 'user')");
                       $stmt->execute(array(
                           'zuser' => $username,
                           'zpass' => password_hash($password, PASSWORD_DEFAULT),
                           'zmail' => $email,
                           'zname' => $fullname,
                           'zpic'  => $avatar
                       ));
                       // Echo Success Message
                       $succesMsg = 'Congrats You Are Now Registered User';
                   }
               }

        }

    }

?>

<!-- Login/Signup Page with Gradient Background -->
<div class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 flex items-center justify-center py-8 px-4">
    <div class="max-w-xs w-full bg-white/5 backdrop-blur-sm rounded-[2rem] p-4 shadow-2xl border border-white/10">
        <!-- Login/Signup Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-user-circle text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-3">
                <span id="loginTab" class="cursor-pointer hover:text-blue-200 transition-colors border-b-2 border-white pb-1">Login</span>
                <span class="text-white/60 mx-3">|</span>
                <span id="signupTab" class="cursor-pointer hover:text-blue-200 transition-colors border-b-2 border-transparent pb-1">Sign Up</span>
            </h1>
            <p id="formDescription" class="text-blue-100 text-sm">Access your account</p>
        </div>

        <!-- Login Form with Glass Effect -->
        <form id="loginForm" class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-lg" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="space-y-4">
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="text"
                        name="username"
                        autocomplete="off"
                        placeholder="Enter your username"
                        required />
                </div>
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Enter your password"
                        required />
                </div>
                <button type="submit" name="login" class="w-full bg-gradient-to-r from-white/20 to-white/30 backdrop-blur-sm border border-white/30 text-white font-semibold py-2 px-4 rounded-lg hover:from-white/30 hover:to-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
                <div class="text-center">

                </div>
            </div>
        </form>

        <!-- Signup Form with Glass Effect -->
        <form id="signupForm" class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-lg hidden" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
            <div class="space-y-4">
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input
                        pattern=".{4,}"
                        title="Username Must Be Between 4 Chars"
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="text"
                        name="username"
                        autocomplete="off"
                        placeholder="Choose a username (min 4 chars)"
                        required />
                </div>
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input
                        minlength="4"
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Create a password"
                        required />
                </div>
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Confirm Password
                    </label>
                    <input
                        minlength="4"
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="password"
                        name="password2"
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                        required />
                </div>
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="email"
                        name="email"
                        placeholder="Enter your email address"
                        required />
                </div>
                <div class="max-w-7xl mx-auto px-4">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-id-card mr-2"></i>Full Name
                    </label>
                    <input
                        class="w-full px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                        type="text"
                        name="fullname"
                        placeholder="Enter your full name"
                        required />
                </div>

                <button type="submit" name="signup" class="w-full bg-gradient-to-r from-green-500/20 to-green-600/30 backdrop-blur-sm border border-green-400/30 text-white font-semibold py-2 px-4 rounded-lg hover:from-green-500/30 hover:to-green-600/40 focus:outline-none focus:ring-2 focus:ring-green-400/50 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
                <div class="text-center">
                    <p class="text-white/70 text-sm">Already have an account?
                        <span id="showLogin" class="text-blue-200 hover:text-white cursor-pointer underline">Login here</span>
                    </p>
                </div>
            </div>
        </form>

        <!-- Error Messages with Gradient Styling -->
        <div class="the-errors text-center space-y-3">
            <?php
                if (!empty($formErrors)) {
                    foreach ($formErrors as $error) {
                        echo '<div class="bg-red-500/20 backdrop-blur-sm border border-red-400/30 text-white px-4 py-3 rounded-lg">';
                        echo '<i class="fas fa-exclamation-triangle mr-2"></i>' . $error;
                        echo '</div>';
                    }
                }

                if (isset($loginError)) {
                    echo '<div class="bg-red-500/20 backdrop-blur-sm border border-red-400/30 text-white px-4 py-3 rounded-lg">';
                    echo '<i class="fas fa-exclamation-triangle mr-2"></i>' . $loginError;
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</div>

<script src="layout/js/login-toggle.js"></script>

<?php
    include $tpl . 'footer.php';
    ob_end_flush();
?>