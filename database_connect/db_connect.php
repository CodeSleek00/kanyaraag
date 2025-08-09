<?php
// db_connect.php
$servername = "localhost";
$username = "u298112699_kanya_raag";
$password = "Divyasingh12";
$dbname = "u298112699_kanyaraag";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");
?>