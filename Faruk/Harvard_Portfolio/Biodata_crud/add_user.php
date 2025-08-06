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
$message_type = "error"; // Default message type

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($name)) {
        $message = "Please enter a name.";
    } elseif (empty($email)) {
        $message = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email format.";
    } elseif (empty($password)) {
        $message = "Please enter a password.";
    } elseif (strlen($password) < 6) {
        $message = "Password must have at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Check if email or name already exists
        $sql_check = "SELECT id FROM users WHERE email = ? OR name = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("ss", $email, $name);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $message = "This email or name is already taken.";
            } else {
                // Hash the password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare an insert statement
                $sql_insert = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("sss", $name, $email, $hashed_password);

                    if ($stmt_insert->execute()) {
                        $success_message = "New user added successfully!";
                        header("Location: read.php?message=" . urlencode($success_message) . "&type=success");
                        exit;
                    } else {
                        $message = "Error: Could not add user. " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                } else {
                    $message = "Error preparing insert statement: " . $conn->error;
                }
            }
            $stmt_check->close();
        } else {
            $message = "Error preparing check statement: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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
            border-color: #1abc9c;
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.2);
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            cursor: pointer;
            color: #b0bec5;
        }
        .btn {
            background: linear-gradient(135deg, #1abc9c, #16a085);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(26, 188, 156, 0.3);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 188, 156, 0.4);
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
        <h2><i class="fas fa-user-plus"></i> Add New User</h2>
        <?php if ($message): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="add_user.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
            </div>
            <button type="submit" class="btn">Add User</button>
        </form>
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