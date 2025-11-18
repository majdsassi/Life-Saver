<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../includes/check_auth.php';

if (! isset($_GET['id']) || ! ctype_digit($_GET['id'])) {
    header('Location: ' . DOMAIN . 'admin/centres.php');
    exit;
}

$centreId = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM centres_collecte WHERE id_centre = ?");
    $stmt->execute([$centreId]);
    $message = '203';
} catch (PDOException $e) {
    error_log('Erreur suppression centre : ' . $e->getMessage());
    $message = '500';
}

header('Location: ' . DOMAIN . 'admin/centres.php?message=' . $message);
exit;
