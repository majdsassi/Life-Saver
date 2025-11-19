<?php
require_once '../config.php';
require_once '../utils/connection.php' ; 
session_start();

if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        try {
            
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE cin = ?"); // Protection Contre les SQL injections hekka 3lh n7oto ?
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify user password and set SESSION
            if ($user && password_verify($_POST['password'], $user["password"])) {
                $_SESSION['user_id'] = $user["id_donneur"];
                header("Location:".DOMAIN."client/index.php");
                exit;
            } else {
                // Creds Ghalta 
               header("Location:".DOMAIN."clinetLogin.php?error=invalid_credentials");
                exit;
            }
            
        } catch (PDOException $e) {
            header("Location:".DOMAIN."clinetLogin.php?error=database_error");
            //echo "ERROR BASE DE DONNNE" ;
            error_log("Database error: " . $e->getMessage());
            exit;
        }
    } else {
        //  username wa ela  password ne9sa
        header("Location: clinetLogin.php?error=missing_fields");
        exit;
    }
} else {
    // jey direct maghir post 
    header("Location:".DOMAIN."clinetLogin.php?error=access_report");
    exit;
}
?>