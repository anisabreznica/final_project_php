<?php
require 'config.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $message = "Registration successful! <a href='user_login.php'>Login here</a>.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {font-family: Arial, sans-serif; background:#f7f7f7; margin:0; padding:0;}
        h2 {color:#333;}
        .container {
            max-width: 500px; margin: 50px auto; background: #fff;
            padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        form input, form textarea {
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
        a {color: #3498db; text-decoration:none;}
        a:hover {text-decoration:underline;}
        p {color: green;}
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post" novalidate>
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Register">
        </form>
        <p><?= $message ?></p>
    </div>
</body>
</html>