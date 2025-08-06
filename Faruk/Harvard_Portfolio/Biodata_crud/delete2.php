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
    $id = trim($_GET['id']);

    // First, get the photo path of the biodata entry to be deleted
    $sql_select_photo = "SELECT photo_path FROM biodata WHERE id = ?";
    
    if ($stmt_select = $conn->prepare($sql_select_photo)) {
        // Bind the ID parameter to the statement
        $stmt_select->bind_param("i", $id);
        
        if ($stmt_select->execute()) {
            $result = $stmt_select->get_result();
            
            if ($result->num_rows == 1) {
                // Fetch the photo path
                $row = $result->fetch_assoc();
                $photo_path_to_delete = $row['photo_path'];

                // Prepare a delete statement for the database
                $sql_delete = "DELETE FROM biodata WHERE id = ?";
                
                if ($stmt_delete = $conn->prepare($sql_delete)) {
                    // Bind the ID parameter to the delete statement
                    $stmt_delete->bind_param("i", $id);

                    if ($stmt_delete->execute()) {
                        // If deletion from DB is successful, attempt to delete the physical photo file
                        if (!empty($photo_path_to_delete) && file_exists($photo_path_to_delete)) {
                            unlink($photo_path_to_delete);
                        }
                        
                        // Set a success message and redirect back to the read page
                        $message = "Biodata record deleted successfully.";
                        header("Location: read.php?message=" . urlencode($message) . "&type=success");
                        exit;
                    } else {
                        $message = "Error deleting record: " . $stmt_delete->error;
                    }
                    $stmt_delete->close();
                } else {
                    $message = "Error preparing delete statement: " . $conn->error;
                }
            } else {
                $message = "No record found with that ID.";
            }
        } else {
            $message = "Error executing select statement: " . $stmt_select->error;
        }
        $stmt_select->close();
    } else {
        $message = "Error preparing select statement: " . $conn->error;
    }
} else {
    $message = "Invalid or missing ID parameter.";
}

// Close the database connection
$conn->close();

// Fallback HTML in case of an error message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Biodata</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 20px; display: inline-block; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <?php if ($message): ?>
        <p class="message error"><?php echo htmlspecialchars($message); ?></p>
        <p><a href="read.php">Back to Biodata List</a></p>
    <?php endif; ?>
</body>
</html>
