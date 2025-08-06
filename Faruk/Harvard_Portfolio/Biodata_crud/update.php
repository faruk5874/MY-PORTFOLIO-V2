<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

?>

<?php

include 'db.php';

$originalName = "";
$originalEmail = "";
$newName = "";
$newEmail = "";
$message = "";

// When the page is first loaded via GET request
if (isset($_GET['name']) && isset($_GET['email'])) {
    $originalName = $_GET['name'];
    $originalEmail = $_GET['email'];

    // For display purposes, pre-fill new name/email fields with original values
    $newName = $originalName;
    $newEmail = $originalEmail;
}

// When the form is submitted via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalName = $_POST['original_name'];
    $originalEmail = $_POST['original_email'];

    $newName = $_POST['new_name'];
    $newEmail = $_POST['new_email'];
    $newPassword = $_POST['new_password']; // Get the new password from the form

    // Initialize an array to hold the update parts for the SQL query
    $updateParts = [];
    $bindTypes = "";
    $bindParams = [];

    // Always update name and email if they are potentially changed
    $updateParts[] = "name=?";
    $bindTypes .= "s";
    $bindParams[] = &$newName;

    $updateParts[] = "email=?";
    $bindTypes .= "s";
    $bindParams[] = &$newEmail;


    // If a new password is provided, hash it and add to update
    if (!empty($newPassword)) {
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateParts[] = "password=?";
        $bindTypes .= "s";
        $bindParams[] = &$hashedNewPassword;
    }

    $sql = "UPDATE users SET " . implode(", ", $updateParts) . " WHERE name=? AND email=?";
    $bindTypes .= "ss"; // Add types for WHERE clause parameters
    $bindParams[] = &$originalName;
    $bindParams[] = &$originalEmail;

    $stmt = $conn->prepare($sql);

    // Use call_user_func_array to bind parameters dynamically
    call_user_func_array([$stmt, 'bind_param'], array_merge([$bindTypes], $bindParams));

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $message = "<p style='color: green; text-align: center;'>Record updated successfully!</p>";
            // Update the originalName and originalEmail to reflect the new values if successful
            $originalName = $newName;
            $originalEmail = $newEmail;
        } else {
            $message = "<p style='color: orange; text-align: center;'>No record found with the provided original Name and Email, or no changes were made.</p>";
        }
    } else {
        $message = "<p style='color: red; text-align: center;'>Error updating record: " . $conn->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        .container {
            text-align: center;
            padding: 20px;
            border: 1px solid black;
            width: 400px;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        label {
            display: inline-block;
            width: 120px;
            text-align: right;
            margin-right: 10px;
        }
        input[type="text"],
        input[type="email"] {
            padding: 8px;
            width: 250px;
            border: 1px solid black;
        }
        input[type="submit"] {
            padding: 10px 20px;
            cursor: pointer;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .password-container {
            position: relative; /* Essential for positioning the toggle icon */
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .password-container input[type="password"],
        .password-container input[type="text"] { /* Target inputs inside this container */
            width: 100%; /* Make input fill container */
            padding-right: 40px; /* Make space for the toggle */
        }
        .password-toggle {
            position: absolute;
            right: 5px;
            top: 50%; /* Center vertically */
            transform: translateY(-50%); /* Adjust for perfect vertical centering */
            cursor: pointer;
            color: #555;
            font-size: 0.9em;
            user-select: none; /* Prevent text selection */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update User</h2>

        <?php echo $message; ?>

        <form method="POST" action="update.php">
            <input type="hidden" name="original_name" value="<?php echo htmlspecialchars($originalName); ?>">
            <input type="hidden" name="original_email" value="<?php echo htmlspecialchars($originalEmail); ?>">

            <p class="section-title">Current User Details (for identification)</p>
            <div class="form-group">
                <label for="display_original_name">Original Name:</label>
                <input type="text" id="display_original_name" value="<?php echo htmlspecialchars($originalName); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="display_original_email">Original Email:</label>
                <input type="email" id="display_original_email" value="<?php echo htmlspecialchars($originalEmail); ?>" readonly>
            </div>

            <p class="section-title">New User Details</p>
            <div class="form-group">
                <label for="new_name">New Name:</label>
                <input type="text" id="new_name" name="new_name" value="<?php echo htmlspecialchars($newName); ?>" required>
            </div>

            <div class="form-group">
                <label for="new_email">New Email:</label>
                <input type="email" id="new_email" name="new_email" value="<?php echo htmlspecialchars($newEmail); ?>" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <div class="password-container">
                    <input type="password" id="new_password" name="new_password" placeholder="Leave blank to keep current">
                    <span class="password-toggle" onclick="togglePasswordVisibility('new_password')">Show</span>
                </div>
            </div>

            <input type="submit" value="Update User">
        </form>

        <p><a href="read.php">Back to User List</a></p>
    </div>

    <script>
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id);
            const toggleSpan = passwordField.closest('.password-container').querySelector('.password-toggle');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleSpan.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleSpan.textContent = 'Show';
            }
        }
    </script>
</body>
</html>