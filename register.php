<?php
session_start();
include 'db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $msg = "❌ Passwords do not match!";
    } elseif (strlen($password) < 5) {
        $msg = "❌ Password must be at least 5 characters!";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "❌ Username already taken!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed);

            if ($stmt->execute()) {
                $msg = "✅ Registration successful! You can now <a href='login.html'>login</a>.";
            } else {
                $msg = "❌ Registration failed, try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Amrit Science Campus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            width: 320px;
        }
        h2 {
            color: #990000;
            text-align: center;
        }
        input {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #990000;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        .message {
            text-align: center;
            margin: 15px 0;
            color: red;
        }
        .message a {
            color: #990000;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Register</h2>
    <?php if ($msg) echo "<div class='message'>$msg</div>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="submit" value="Register">
    </form>
    <p style="text-align:center;">Already have an account? <a href="login.html">Login here</a></p>
</div>

</body>
</html>
