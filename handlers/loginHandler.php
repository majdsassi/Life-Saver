<?php
require_once '../config.php';
require_once '../utils/connection.php' ; 
session_start();

if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        try {
            
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = ?"); // Protection Contre les SQL injections hekka 3lh n7oto ?
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify user password and set SESSION
            if ($user && password_verify($_POST['password'], $user["mot_de_passe"])) {
                $_SESSION['user_id'] = $user["id_utilisateur"];
                $_SESSION['user_role'] = $user["role"] ; 
                header("Location:".DOMAIN.strtolower($user['role'])."/index.php");
                exit;
            } else {
                // Creds Ghalta 
               header("Location:".DOMAIN."login.php?error=invalid_credentials");
               echo "ERROR mch majwoud" ;
                exit;
            }
            
        } catch (PDOException $e) {
            header("Location:".DOMAIN."login.php?error=database_error");
            echo "ERROR BASE DE DONNNE" ;
            error_log("Database error: " . $e->getMessage());
            exit;
        }
    } else {
        //  username wa ela  password ne9sa
        header("Location: login.php?error=missing_fields");
        echo "ERROR BASE DE DONNNE" ;
        exit;
    }
} else {
    // jey direct maghir post 
    header("Location:".DOMAIN."login.php?error=access_report");
    exit;
}
?>