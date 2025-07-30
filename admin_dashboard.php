<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($username, $role);
$stmt->fetch();
$stmt->close();

// Fetch animals
$animals = $conn->query("SELECT * FROM animals");

// Fetch adopted animals with user info
$adopted_query = "
    SELECT a.name AS animal_name, a.image, u.name AS user_name, ad.adopted_at
    FROM adoptions ad
    JOIN animals a ON ad.animal_id = a.id
    JOIN users u ON ad.user_id = u.id
    ORDER BY ad.adopted_at DESC
";
$adopted_result = $conn->query($adopted_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .top-bar {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .logout {
            float: right;
            background: red;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .animal-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            width: 250px;
            box-shadow: 0 0 8px #ccc;
            text-align: center;
        }
        .animal-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 6px;
        }
        .buttons a {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            background: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .adopted-table {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px #ccc;
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f5f5f5;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h1>Welcome, <?= htmlspecialchars($username) ?> (Admin)</h1>
    <a href="logout.php" class="logout">Logout</a>
</div>

<h2>Manage Animals</h2>
<div class="grid">
    <?php while ($row = $animals->fetch_assoc()): ?>
        <div class="animal-card">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p><strong>Breed:</strong> <?= htmlspecialchars($row['breed']) ?></p>
            <p><strong>Age:</strong> <?= (int)$row['age'] ?></p>
            <div class="buttons">
                <a href="edit_animal.php?id=<?= $row['id'] ?>">Edit</a>
                <a href="delete_animal.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div class="adopted-table">
    <h2>Adopted Animals</h2>
    <?php if ($adopted_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Animal</th>
                <th>Image</th>
                <th>Adopted By</th>
                <th>Adopted At</th>
            </tr>
            <?php while ($adopted = $adopted_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($adopted['animal_name']) ?></td>
                    <td><img src="<?= htmlspecialchars($adopted['image']) ?>" alt="" style="width: 80px; border-radius: 6px;"></td>
                    <td><?= htmlspecialchars($adopted['user_name']) ?></td>
                    <td><?= htmlspecialchars($adopted['adopted_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No adoptions yet.</p>
    <?php endif; ?>
</div>

</body>
</html>