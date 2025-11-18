<?php
// require_once "C:\\xampp\\htdocs\\Life-Saver\\config.php";
require_once __DIR__ . '/../config.php';

try {
    // 1. Définition de la chaîne de connexion (DSN)
    // mysql:host=...;dbname=...
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;

    // 2. Création de l'objet PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    // 3. Configuration importante : Afficher les exceptions/erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Gestion de l'erreur
    die("Erreur de connexion : " . $e->getMessage());
}
