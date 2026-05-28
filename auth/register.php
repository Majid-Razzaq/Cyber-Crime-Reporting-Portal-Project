<?php
session_start();
require "../config/database_connection.php";
require "../includes/auth.php";

redirectIfLoggedIn();

if (isset($_POST['register'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Check password match
    if ($password !== $confirm) {

        $_SESSION['error'] = "Passwords do not match";

    } else {

        // Check existing email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {

            $_SESSION['error'] = "Email already exists";

        } else {

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $query = mysqli_query(
                $conn,
                "INSERT INTO users (full_name, email, phone, password)
                 VALUES ('$name', '$email', '$phone', '$hashedPassword')"
            );

            if ($query) {

                $_SESSION['success'] = "Registration successful. Please login.";

                header("Location: login.php");
                exit;

            } else {

                $_SESSION['error'] = "Registration failed";

            }
        }
    }
}

?>

<?php include "../includes/header.php"; ?>

<section class="section-5">
    <div class="container my-5">

        <?php include "../includes/message.php"; ?>
        <?php displayMessage(); ?>

        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Email*</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Phone*</label>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter Phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Please confirm Password" required>
                        </div>
                        <button name="register" class="btn btn-primary mt-2" type="submit">Register</button>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>