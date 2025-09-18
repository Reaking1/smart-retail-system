<?php
session_start();
include __DIR__ . "/../../includes/db.php";

// If already logged in, redirect
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if ($admin && password_verify($password, $admin['password'])) {
        // Login successful
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container { width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
        h1 { text-align: center; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; }
        button:hover { background: #0056b3; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Enter username" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
