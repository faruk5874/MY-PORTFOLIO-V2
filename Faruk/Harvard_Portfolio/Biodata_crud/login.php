<?php
session_start();

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id_from_db, $username_from_db, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user_id_from_db;
            $_SESSION['username'] = $username_from_db;
            header("Location: read.php");
            exit;
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "Invalid email or password.";
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
    <title>Login</title>
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
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        .toggle-password {
            position: absolute;
            right: 18px;
            cursor: pointer;
            color: #b0bec5;
        }
        .auth-container button {
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
        }
        .auth-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
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
        <h1>Login</h1>
        <?php if (!empty($message)) echo "<p class='error-message'>$message</p>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
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
        });
    </script>
</body>
</html>