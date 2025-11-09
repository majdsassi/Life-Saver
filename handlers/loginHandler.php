<?php
require_once '../config.php';
require_once '../utils/connection.php' ; 
session_start();

if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        try {
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            // Verify user password and set SESSION
            if ($user && password_verify($_POST['password'], $user->password)) {
                $_SESSION['user_id'] = $user->ID;
                header("Location: index.php");
                exit;
            } else {
                // Creds Ghalta 
                header("Location: login.php?error=invalid_credentials");
                exit;
            }
            
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header("Location: login.php?error=database_error");
            exit;
        }
    } else {
        //  username wa ela  password ne9sa
        header("Location: login.php?error=missing_fields");
        exit;
    }
} else {
    // jey direct maghir post 
    header("Location: login.php");
    exit;
}
?>