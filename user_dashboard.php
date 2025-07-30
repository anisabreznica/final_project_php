<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}
require 'config.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

$result = $conn->query("SELECT * FROM animals");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
        }
        .header a {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .card h3 {
            margin: 10px 0 5px;
        }
        .card a {
            display: inline-block;
            margin-top: 8px;
            background: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .card a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
        <a href="logout.php">Logout</a>
    </div>

    <div class="grid">
        <?php while ($animal = $result->fetch_assoc()): ?>
            <div class="card">
                <?php if (!empty($animal['image'])): ?>
                    <img src="<?= htmlspecialchars($animal['image']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/250x180?text=No+Image" alt="No Image">
                <?php endif; ?>
                <h3><?= htmlspecialchars($animal['name']) ?></h3>
                <a href="animal_details.php?id=<?= $animal['id'] ?>">View Description</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>