<?php

require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../includes/check_auth.php';

$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($userId <= 0) {
    header('Location: ' . DOMAIN . 'admin/utilisateurs.php?error=missing_id');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM utilisateurs WHERE id_utilisateur = ?');
$stmt->execute([$userId]);

header('Location: ' . DOMAIN . 'admin/utilisateurs.php?message=203');
exit;
