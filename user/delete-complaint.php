<?php

require "../config/database_connection.php";
include "../includes/header.php";

// Check Login
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$complaint_id = $_GET['id'];

/* Delete only user's own complaint */
$delete = mysqli_query(
    $conn,
    "DELETE FROM complaints 
     WHERE id = '$complaint_id' 
     AND user_id = '$user_id'"
);

if ($delete) {

    $_SESSION['success'] = "Complaint deleted successfully.";
} else {

    $_SESSION['error'] = "Failed to delete complaint.";
}

header("Location: dashboard.php");
exit;
