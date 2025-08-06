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
$message_type = "";
$user_data = []; // Initialize to hold current user data

// Logic to fetch user data if ID is present in GET
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);
    $sql_select = "SELECT id, name, email FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql_select)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user_data = $result->fetch_assoc();
            } else {
                header("Location: read.php?message=" . urlencode("No user found with that ID."));
                exit;
            }
        } else {
            $message = "Error executing select statement: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    } else {
        $message = "Error preparing select statement: " . $conn->error;
        $message_type = "error";
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // This block handles the form submission for updating user data
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_new_password = trim($_POST['confirm_new_password']);

    // Re-fetch user data to display in form even if there's an error
    $sql_select_current = "SELECT id, name, email FROM users WHERE id = ?";
    if ($stmt_select_current = $conn->prepare($sql_select_current)) {
        $stmt_select_current->bind_param("i", $id);
        $stmt_select_current->execute();
        $user_data = $stmt_select_current->get_result()->fetch_assoc();
        $stmt_select_current->close();
    }

    // Basic validation
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Name and a valid email are required.";
        $message_type = "error";
    } else {
        // Check if email or name already exists for *another* user
        $sql_check_duplicate = "SELECT id FROM users WHERE (email = ? OR name = ?) AND id != ?";
        if ($stmt_check = $conn->prepare($sql_check_duplicate)) {
            $stmt_check->bind_param("ssi", $email, $name, $id);
            $stmt_check->execute();
            if ($stmt_check->get_result()->num_rows > 0) {
                $message = "This email or name is already taken by another user.";
                $message_type = "error";
            } else {
                $password_update_sql = "";
                $params = [$name, $email];
                $types = "ss";

                // Handle password change if provided
                if (!empty($new_password)) {
                    if (strlen($new_password) < 6) {
                        $message = "New password must have at least 6 characters.";
                        $message_type = "error";
                    } elseif ($new_password !== $confirm_new_password) {
                        $message = "New passwords do not match.";
                        $message_type = "error";
                    } else {
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $password_update_sql = ", password = ?";
                        $params[] = $hashed_new_password;
                        $types .= "s";
                    }
                }

                if (empty($message)) { // Only proceed if no validation errors
                    $params[] = $id;
                    $types .= "i";
                    $sql_update = "UPDATE users SET name = ?, email = ? " . $password_update_sql . " WHERE id = ?";

                    if ($stmt_update = $conn->prepare($sql_update)) {
                        $stmt_update->bind_param($types, ...$params);

                        if ($stmt_update->execute()) {
                            $message = "User updated successfully!";
                            $message_type = "success";
                            // Re-fetch the updated data to display in the form
                            $user_data['name'] = $name;
                            $user_data['email'] = $email;
                        } else {
                            $message = "Error updating user: " . $stmt_update->error;
                             $message_type = "error";
                        }
                        $stmt_update->close();
                    }
                }
            }
            $stmt_check->close();
        }
    }
} else {
    header("Location: read.php?message=" . urlencode("Invalid request or missing user ID."));
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 450px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #34495e;
            font-weight: 700;
        }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            text-align: center;
        }
        .message.error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .message.success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #5d6d7e;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            cursor: pointer;
            color: #b0bec5;
        }
        .btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        .back-link {
            display: block;
            margin-top: 25px;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-edit"></i> Edit User</h2>
        <?php if ($message): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (!empty($user_data)): ?>
            <form action="edit_user.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_data['id']); ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password (leave blank to keep current):</label>
                    <div class="password-wrapper">
                        <input type="password" id="new_password" name="new_password">
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_new_password" name="confirm_new_password">
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <button type="submit" class="btn">Update User</button>
            </form>
        <?php else: ?>
            <p>User data could not be loaded.</p>
        <?php endif; ?>
        <a href="read.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const passwordField = this.closest('.password-wrapper').querySelector('input');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    passwordField.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
</body>
</html>