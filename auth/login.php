<?php

session_start();
require "../config/database_connection.php";
require "../includes/auth.php";

redirectIfLoggedIn();

if (isset($_POST['login'])) {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {

        $_SESSION['error'] = "Please enter email and password";
    } else {

        /* -----------------------------
           1. CHECK ADMIN TABLE FIRST
        ------------------------------*/
        $adminQuery = "SELECT id, email, password 
                       FROM admins 
                       WHERE email='$email'";

        $adminResult = mysqli_query($conn, $adminQuery);

       if (mysqli_num_rows($adminResult) === 1) {

    $admin = mysqli_fetch_assoc($adminResult);

    if ($password === $admin['password']) {

        $_SESSION['user_id']   = $admin['id'];
        $_SESSION['user_name'] = $admin['email'];
        $_SESSION['role']      = "admin";

        header("Location: ../admin/dashboard.php");
        exit;
    }
}

        /* -----------------------------
           2. CHECK USER TABLE
        ------------------------------*/
        $userQuery = "SELECT id, full_name, password 
                      FROM users 
                      WHERE email='$email'";

        $userResult = mysqli_query($conn, $userQuery);

        if (mysqli_num_rows($userResult) === 1) {

            $user = mysqli_fetch_assoc($userResult);

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['role']      = "user";

                header("Location: ../user/dashboard.php");
                exit;
            } else {

                $_SESSION['error'] = "Invalid login credentials";
            }
        } else {

            $_SESSION['error'] = "Invalid login credentials";
        }
    }
}

?>

<?php include "../includes/header.php"; ?>

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>

        <?php
        include "../includes/message.php";
        displayMessage();
        ?>

        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Login</h1>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="" class="mb-2">Email*</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="example@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                        </div>
                        <div class="justify-content-between d-flex">
                            <button name="login" class="btn btn-primary mt-2" type="submit">Login</button>
                            <a href="#" class="mt-3">Forgot Password?</a>
                        </div>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Do not have an account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
        <div class="py-lg-5">&nbsp;</div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>