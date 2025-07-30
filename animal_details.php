<?php
session_start();
require 'config.php';

if (!isset($_GET['id'])) {
    header("Location: animals.php");
    exit;
}

$animal_id = (int)$_GET['id'];

// Get animal info
$stmt = $conn->prepare("SELECT a.*, 
    (SELECT COUNT(*) FROM adoptions WHERE animal_id = a.id) AS adopted 
    FROM animals a WHERE id = ?");
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Animal not found.";
    exit;
}

$animal = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($animal['name']) ?> Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0; padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        h2 { margin-top: 20px; }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background: #27ae60;
            border-radius: 5px;
        }
        .btn.disabled {
            background: gray;
            pointer-events: none;
        }
    </style>
</head>
<body>
<div class="container">
    <img src="<?= htmlspecialchars($animal['image']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
    <h2><?= htmlspecialchars($animal['name']) ?></h2>
    <p><strong>Breed:</strong> <?= htmlspecialchars($animal['breed']) ?></p>
    <p><strong>Age:</strong> <?= (int)$animal['age'] ?> years</p>
    <p><strong>Description:</strong> <?= htmlspecialchars($animal['description']) ?></p>

    <?php if ($animal['adopted']): ?>
        <a class="btn disabled">Already Adopted</a>
    <?php else: ?>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="adopt.php?id=<?= $animal['id'] ?>" class="btn">Adopt</a>
        <?php else: ?>
            <a href="user_login.php" class="btn">Login to Adopt</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
