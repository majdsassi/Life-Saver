<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();

require_once __DIR__ . '/../config.php';

header('Location: ' . DOMAIN . 'login.php');
exit();
