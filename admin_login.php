<?php
session_start();
require 'config.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Wrong password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {font-family: Arial, sans-serif; background:#f7f7f7; margin:0; padding:0;}
        h2 {color:#333;}
        .container {
            max-width: 400px; margin: 50px auto; background: #fff;
            padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        form input {
            width: 100%; padding: 10px; margin: 8px 0 20px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        input[type="submit"] {
            background: #3498db; color: white; border:none;
            padding: 12px 20px; cursor: pointer; border-radius: 5px;
        }
        input[type="submit"]:hover {
            background: #2980b9;
        }
        p.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form method="post" novalidate>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <?php if($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
