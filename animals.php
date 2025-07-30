<?php
session_start();
require 'config.php';

$result = $conn->query("SELECT a.*, 
    (SELECT COUNT(*) FROM adoptions WHERE animal_id = a.id) AS adopted
    FROM animals a");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Animals</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; margin: 0; padding: 0; }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        h3 { margin: 10px 0 5px; }
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        .adopt { background: #27ae60; }
        .adopted { background: gray; pointer-events: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Animals Available for Adoption</h2>
    <div class="grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= htmlspecialchars($row['breed']) ?></p>

                <?php if ($row['adopted']): ?>
                    <div class="btn adopted">Already Adopted</div>
                <?php else: ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="btn adopt" href="adopt.php?id=<?= $row['id'] ?>">Adopt</a>
                    <?php else: ?>
                        <a class="btn adopt" href="user_login.php">Login to Adopt</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>