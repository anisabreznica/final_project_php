<?php
session_start();
require 'config.php';

$result = $conn->query("SELECT * FROM animals LIMIT 6"); // Show just 6 for homepage
?>

<!DOCTYPE html>
<html>
<head>
    <title>Animal Adoption</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0; padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .top-links {
            text-align: center;
            margin-bottom: 20px;
        }
        .top-links a {
            margin: 0 10px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .top-links a:hover {
            text-decoration: underline;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
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
    <h1>Welcome to Animal Adoption Center</h1>

    <div class="top-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="user_dashboard.php">User Dashboard</a>
        <?php elseif (isset($_SESSION['admin_id'])): ?>
            Welcome Admin |
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="user_login.php">User Login</a>
            <a href="admin_login.php">Admin Login</a>
        <?php endif; ?>
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
                <p><?= htmlspecialchars($animal['breed']) ?></p>

                <?php if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
                    <a href="animal_details.php?id=<?= $animal['id'] ?>">View More</a>
                <?php else: ?>
                    <a href="user_login.php">Login to View</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
