<?php
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstName = trim($_POST['first_name']);
    $lastName  = trim($_POST['last_name']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];

    $fullName = $firstName . " " . $lastName;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO users (full_name, email, password_hash)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $fullName, $email, $passwordHash);

        if ($stmt->execute()) {
            header("Location: signin.php");
            exit;
        } else {
            $error = "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account - FINOVA</title>
    <link rel="stylesheet" href="assets/css/signup.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>


<body class="auth-page">
<div class="auth-card">

    <a href="index.html" class="back-link">
        <i data-lucide="arrow-left"></i> Back to Dashboard
    </a>

    <h2>Create Account</h2>
    <p class="auth-subtitle">Start your journey with FINOVA today</p>

    <?php if ($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-row">
            <div class="input-group">
                <label>First Name</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="input-group">
                <label>Last Name</label>
                <input type="text" name="last_name" required>
            </div>
        </div>

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" minlength="8" required>
        </div>

        <button type="submit" class="btn-primary-full">Create Account</button>
    </form>

    <p class="switch-auth">
        Already have an account? <a href="signin.php">Sign in</a>
    </p>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
