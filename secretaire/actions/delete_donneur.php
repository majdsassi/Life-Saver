<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../includes/check_auth.php';

if (! isset($_GET['id'])) {
    header('Location: ' . DOMAIN . 'secretaire/donneurs.php?message=204');
    exit();
}

$stmt = $pdo->prepare("DELETE FROM donneurs WHERE id_donneur = ?");
$stmt->execute([$_GET['id']]);

header('Location: ' . DOMAIN . 'secretaire/donneurs.php?message=203');
exit();
