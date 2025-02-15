<?php
$host = "localhost"; 
$user = "anh"; 
$pass = "123456789";
$dbname = "wander_whimpsy"; 

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
