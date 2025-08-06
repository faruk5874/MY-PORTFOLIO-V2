<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$db="mydb";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $db, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "";
?>