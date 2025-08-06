<?php
// Alternative connection attempt
$servername = "127.0.0.1";
$username = "root";
$password = "";
$port = 3306;

// Try connecting without specifying database
try {
    $conn = new mysqli($servername, $username, $password, "", $port);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected successfully without specifying database!";
    
    // Now check if our database exists
    $result = $conn->query("SHOW DATABASES LIKE 'mydb'");
    
    if ($result->num_rows > 0) {
        echo "<br>Database 'mydb' exists.";
    } else {
        echo "<br>Database 'mydb' does NOT exist. Creating it now...";
        if ($conn->query("CREATE DATABASE mydb")) {
            echo "<br>Database created successfully!";
        } else {
            echo "<br>Error creating database: " . $conn->error;
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Connection error: " . $e->getMessage();
}
?>
