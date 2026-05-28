<?php

$host = "localhost";
$username = "root";
$port = 3307;  
$password = "";
$database = "cyber_crime_portal";

$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>