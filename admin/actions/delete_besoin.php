<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../includes/check_auth.php';

if (! isset($_GET['id']) || ! is_numeric($_GET['id'])) {
    header('Location: ' . DOMAIN . 'admin/besoins.php');
    exit;
}

$besoinId = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM besoins WHERE id_besoin = ?");
    $stmt->execute([$besoinId]);
    $message = '203';
} catch (PDOException $e) {
    error_log('Erreur suppression besoin : ' . $e->getMessage());
    $message = '500';
}

header('Location: ' . DOMAIN . 'admin/besoins.php?message=' . $message);
exit;
