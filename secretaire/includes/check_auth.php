<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config.php';

if (! isset($allowedRoles) || ! is_array($allowedRoles) || $allowedRoles === []) {
    $allowedRoles = ['SECRETAIRE'];
}

if (! isset($_SESSION['user_role']) || ! in_array($_SESSION['user_role'], $allowedRoles, true)) {
    header('Location: ' . DOMAIN . 'login.php?error=unauthorized');
    exit;
}
