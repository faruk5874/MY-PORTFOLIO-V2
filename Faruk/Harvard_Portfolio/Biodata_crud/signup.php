<?php
session_start();

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $message = "<p class='error-message'>All fields are required.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p class='error-message'>Invalid email format.</p>";
    } elseif (strlen($password) < 6) {
        $message = "<p class='error-message'>Password must be at least 6 characters long.</p>";
    } else {
        $check_email_sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($check_email_sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $message = "<p class='error-message'>This email is already registered. Please use a different one.</p>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

                if ($stmt_insert = $conn->prepare($sql)) {
                    $stmt_insert->bind_param("sss", $name, $email, $hashed_password);
                    if ($stmt_insert->execute()) {
                        header("Location: login.php?message=" . urlencode("Account created successfully. You can now log in."));
                        exit;
                    } else {
                        $message = "<p class='error-message'>Error creating account: " . $stmt_insert->error . "</p>";
                    }
                    $stmt_insert->close();
                } else {
                    $message = "<p class='error-message'>Error preparing statement: " . $conn->error . "</p>";
                }
            }
            $stmt->close();
        } else {
            $message = "<p class='error-message'>Error checking email: " . $conn->error . "</p>";
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
    <title>Sign Up</title>
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
        .auth-container {
            width: 400px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .auth-container h1 {
            margin-bottom: 25px;
            color: #34495e;
            font-weight: 700;
        }
        .auth-container .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .auth-container input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .auth-container input:focus {
            outline: none;
            border-color: #2ecc71;
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
        }
        .toggle-password {
            position: absolute;
            right: 18px;
            cursor: pointer;
            color: #b0bec5;
        }
        .auth-container button {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        .auth-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }
        .auth-container p {
            margin-top: 25px;
            color: #7f8c8d;
        }
        .auth-container a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .auth-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Create Your Account</h1>
        <?php if (!empty($message)) echo $message; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
    
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordField = this.previousElementSibling;
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
    </script>
</body>
</html>