<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = (int)$_POST['age'];
    $breed = $_POST['breed'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("UPDATE animals SET name=?, age=?, breed=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sisssi", $name, $age, $breed, $description, $image, $id);

    if ($stmt->execute()) {
        $msg = "Animal updated successfully!";
    }
}

$stmt = $conn->prepare("SELECT * FROM animals WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();

if (!$animal) {
    die("Animal not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Animal</title>
    <style>
        body {font-family: Arial, sans-serif; background:#f7f7f7; margin:0; padding:0;}
        .container {
            max-width: 600px; margin: 50px auto; background: #fff;
            padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            color: #333;
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
        p.msg {
            color: green;
        }
        a {
            color: #3498db; text-decoration:none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Animal</h2>
        <form method="post" novalidate>
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($animal['name']) ?>" required>
            <label>Age:</label>
            <input type="number" name="age" min="0" value="<?= (int)$animal['age'] ?>" required>
            <label>Breed:</label>
            <input type="text" name="breed" value="<?= htmlspecialchars($animal['breed']) ?>" required>
            <label>Description:</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($animal['description']) ?></textarea>
            <label>Image URL:</label>
            <input type="text" name="image" value="<?= htmlspecialchars($animal['image']) ?>">
            <input type="submit" value="Update Animal">
        </form>
        <p class="msg"><?= htmlspecialchars($msg) ?></p>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>