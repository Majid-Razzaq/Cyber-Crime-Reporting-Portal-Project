<?php

session_start();

/* If user is logged in */
if (isset($_SESSION['user_id'], $_SESSION['role'])) {

    // Admin redirect
    if ($_SESSION['role'] === 'admin') {

        header("Location: admin/dashboard.php");
        exit;
    }

    // Normal user redirect
    if ($_SESSION['role'] === 'user') {

        header("Location: user/dashboard.php");
        exit;
    }
}

/* Not logged in */
header("Location: login.php");
exit;
