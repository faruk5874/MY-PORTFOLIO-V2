<?php
session_start();

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
include 'db.php';

$message = "";

// Check if an ID is provided in the URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id_to_delete = trim($_GET['id']);

    // Prevent deleting the currently logged-in user (optional, but good practice)
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id_to_delete) {
        $message = "Error: You cannot delete your own account while logged in.";
        header("Location: read.php?message=" . urlencode($message) . "&type=error"); // Pass type for styling
        exit;
    }

    // Prepare a delete statement
    $sql_delete = "DELETE FROM users WHERE id = ?";

    if ($stmt_delete = $conn->prepare($sql_delete)) {
        $stmt_delete->bind_param("i", $id_to_delete);

        if ($stmt_delete->execute()) {
            $message = "User deleted successfully.";
            header("Location: read.php?message=" . urlencode($message) . "&type=success");
            exit;
        } else {
            $message = "Error deleting user: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    } else {
        $message = "Error preparing delete statement: " . $conn->error;
    }
} else {
    $message = "Invalid or missing user ID parameter.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 20px; display: inline-block; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <?php
    $display_message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : $message;
    $message_type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : (strpos($display_message, 'Error') !== false ? 'error' : 'success');
    ?>
    <p class="message <?php echo $message_type; ?>"><?php echo $display_message; ?></p>
    <p><a href="read.php">Back to Dashboard</a></p>
</body>
</html>