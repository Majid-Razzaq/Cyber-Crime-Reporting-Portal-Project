<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//    Redirect Logged-in Users
function redirectIfLoggedIn()
{

    if (isset($_SESSION['user_id'], $_SESSION['role'])) {

        // Admin
        if ($_SESSION['role'] === 'admin') {

            header("Location: ../admin/dashboard.php");
            exit;
        }

        // User
        if ($_SESSION['role'] === 'user') {

            header("Location: ../user/dashboard.php");
            exit;
        }
    }
}
//    Require Login

function requireLogin()
{

    if (!isset($_SESSION['user_id'])) {

        $_SESSION['error'] = "Please login first.";

        header("Location: ../auth/login.php");
        exit;
    }
}

//    Admin Only
function requireAdmin()
{

    requireLogin();

    if ($_SESSION['role'] !== 'admin') {

        $_SESSION['error'] = "Unauthorized Access";

        header("Location: ../auth/login.php");
        exit;
    }
}

//    User Only
function requireUser()
{

    requireLogin();

    if ($_SESSION['role'] !== 'user') {

        $_SESSION['error'] = "Unauthorized Access";

        header("Location: ../auth/login.php");
        exit;
    }
}
