<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$animal_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if already adopted
$check = $conn->prepare("SELECT * FROM adoptions WHERE animal_id = ?");
$check->bind_param("i", $animal_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    header("Location: animals.php?msg=already_adopted");
    exit;
}

// Adopt
$stmt = $conn->prepare("INSERT INTO adoptions (user_id, animal_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $animal_id);
$stmt->execute();

header("Location: animals.php?msg=adopted");
exit;