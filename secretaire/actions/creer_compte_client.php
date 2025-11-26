<?php

require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../includes/check_auth.php';
require_once __DIR__ . '/../utils/helpers.php';

$donneurId = 0;

if (!isset($_GET['id'])) {
  header('Location: ' . DOMAIN . 'secretaire/donneurs.php');
}

$donneurId = $_GET['id'];

// if (isset($_GET['id'])) {
//   header('Location: ' . DOMAIN . 'secretaire/donneurs.php?error=missing_id');
//   exit;
// }

$donneur = get_donneur_by_id($donneurId);

if (!$donneur) {
  header('Location: ' . DOMAIN . 'secretaire/donneurs.php?error=not_found');
  exit;
}

$errors = [];

$stmt1 = $pdo->query("SELECT * FROM donneurs WHERE id_donneur = $donneurId");
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("INSERT INTO clients VALUES (:id, :cin, :pwd)");

$password_gen = password_hash($row1["nom"] . $row1["cin"], PASSWORD_DEFAULT);

$stmt->bindParam(':id', $row1["id_donneur"]);
$stmt->bindParam(':cin', $row1["cin"]);
$stmt->bindParam(':pwd', $password_gen);

$stmt->execute();

header("Location: " . DOMAIN . 'secretaire/donneurs.php?message=999');
