<?php
// connect.php

// Database connection details
$servername = "localhost";
$username = "p5nsxwki604v_root";
$passworddb = "Td11112002@";
$dbname = "p5nsxwki604v_congtyvienthong";

// Create connection
$conn = new mysqli($servername, $username, $passworddb, $dbname);
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
