<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require 'config.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM animals WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php");
exit;