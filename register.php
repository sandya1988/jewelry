<?php
require_once 'connection.php'; 

// Start session
session_start();

$register_error = '';
$login_error = '';
$registration_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $register_error = "Error: Email already exists.";
        } else {
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if (!$stmt->execute()) {
                $register_error = "Error: " . $stmt->error;
            } else {
                $user_id = $stmt->insert_id;
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;

                $registration_success = "Registration successful.";
                header("Location: /index.php");
                exit;
            }
            $stmt->close();
        }
    } else {
        $register_error = "All fields are required.";
    }
    $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password, role_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password, $role_id);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role_id'] = $role_id;

                if ($role_id == 2) {
                    header("Location: /admin.php");
                    exit;
                } else {
                    header("Location: /index.php");
                    exit;
                }
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "No user found with this email.";
        }

        $stmt->close();
    } else {
        $login_error = "All fields are required.";
    }

    $conn->close();
}

?>

<?php include 'components/header.php'; ?>

<section class="banner_main">
    <img src="images/bg-login.jpg" alt="" class="w-100">
    <div class="booking_ocline">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="book_room">
                        <h1>Register</h1>
                        <form class="book_now" action="" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="username" style="width: 100px;">Username</label>
                                    <input type="text" id="username" name="username" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="email" style="width: 100px;">Email</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="password" style="width: 100px;">Password</label>
                                    <input type="password" id="password" name="password" required>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="register" class="book_btn mt-4">Register</button>
                                </div>
                                <?php if ($register_error) : ?>
                                    <div class="col-md-12">
                                        <p style="color: red;"><?php echo $register_error; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if ($registration_success) : ?>
                                    <div class="col-md-12">
                                        <p style="color: green;"><?php echo $registration_success; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="book_room">
                        <h1>Login</h1>
                        <form class="book_now" action="" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="login_email" style="width: 100px;">Email</label>
                                    <input type="email" id="login_email" name="email" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="login_password" style="width: 100px;">Password</label>
                                    <input type="password" id="login_password" name="password" required>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="login" class="book_btn mt-4">Login</button>
                                </div>
                                <?php if ($login_error) : ?>
                                    <div class="col-md-12">
                                        <p style="color: red;"><?php echo $login_error; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>