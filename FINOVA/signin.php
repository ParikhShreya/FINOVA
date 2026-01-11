<?php
require_once "config/db.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("
        SELECT id, full_name, password_hash 
        FROM users 
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password_hash'])) {

            // Login success → create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['full_name'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In - FINOVA</title>
    <link rel="stylesheet" href="assets/css/signup.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="auth-page">
<div class="auth-card">

    <a href="index.php" class="back-link">
        <i data-lucide="arrow-left"></i> Back 
    </a>

    <h2>Sign In</h2>
    <p class="auth-subtitle">Welcome back to FINOVA</p>

    <?php if ($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn-primary-full">
            Sign In
        </button>
    </form>

    <p class="switch-auth">
        Don’t have an account?
        <a href="signup.php">Create one</a>
    </p>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
