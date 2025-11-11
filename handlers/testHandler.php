<?php
require_once '../config.php';
require_once '../utils/connection.php';
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "MEDECIN") {
    header("Location:" . DOMAIN . "login.php?error=401");
    exit;
}

if (!empty($_POST)) {
    
    if (isset($_POST['id_don']) && isset($_POST['confirm']) && isset($_POST['note'])) {
        try {
            
            $id_don = $_POST['id_don'] ;
            $confirm = $_POST['confirm'] ;
            $note = trim($_POST['note']);
            
            if ($id_don === false || $id_don <= 0) {
                header("Location:" . DOMAIN . "medecin/dons.php");
                exit;
            }
            
            if ($confirm === false || ($confirm != 0 && $confirm != 1)) {
                header("Location:" . DOMAIN . "medecin/dons.php");
                exit;
            }
            
            
            
            $stmt = $pdo->prepare(" SELECT * FROM dons WHERE id_don = ? ");
            $stmt->execute($id_don);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                header("Location:" . DOMAIN . "medecin/dons.php");
                exit;
            }
            
            $updateStmt = $pdo->prepare("
                UPDATE dons 
                SET 
                statut = ?,
                WHERE id_don = ?
            ");
            
            
            $statut_test = ($confirm == 1) ? 'VALIDE' : 'REJETÉ';
            
           $insertStmt = $pdo->prepare("INSERT INTO `tests_don`(`id_don`, `est_conforme`, `notes_medecin`) VALUES ('?','?','?')");
           $insertStmt->execute([$id_don, $confirm , $note]) ;
        if($insertStmt->rowCount()>0){
            $updateStmt->execute([
                $statut_test,
                $id_don
            ]);
            
            if ($updateStmt->rowCount() > 0) {
                
                header("Location:" . DOMAIN . "medecin/dons.php?success=201");
                exit;
            } else {
                
                header("Location:" . DOMAIN . "medecin/?error=404");
                exit;
            }
        }
        else {
                
                header("Location:" . DOMAIN . "medecin/?error=404");
                exit;
        }

            
        } catch (PDOException $e) {
            error_log("Database error in testHandler: " . $e->getMessage());
            header("Location:" . DOMAIN . "medecin/");
            exit;
        }
    } else {
        
        header("Location:" . DOMAIN . "medecin/test.php");
        exit;
    }
} else {
    header("Location:" . DOMAIN . "medecin/dons.php");
    exit;
}
?>