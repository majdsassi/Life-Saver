<?php
session_start();
require_once '../config.php';
require_once '../utils/connection.php';

if (! isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'MEDECIN') {
    header('Location:' . DOMAIN . 'login.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:' . DOMAIN . 'medecin/tests.php');
    exit;
}

$centreId = $_SESSION['centre_id'] ?? null;
$idDon    = $_POST['id_don'] ?? '';
$confirm  = $_POST['confirm'] ?? '';
$note     = trim($_POST['note'] ?? '');

if (! ctype_digit((string) $idDon) || ! in_array($confirm, ['0', '1'], true) || $note === '' || ! $centreId) {
    header('Location:' . DOMAIN . 'medecin/tests.php?message=400');
    exit;
}

$idDon      = (int) $idDon;
$isConforme = (int) $confirm;

try {
    $stmt = $pdo->prepare(
        "SELECT id_don, statut, id_centre
         FROM dons
         WHERE id_don = ? AND id_centre = ?"
    );
    $stmt->execute([$idDon, $centreId]);
    $don = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $don || $don['statut'] !== 'EN STOCK') {
        header('Location:' . DOMAIN . 'medecin/tests.php?message=404');
        exit;
    }

    $already = $pdo->prepare("SELECT COUNT(*) FROM tests_don WHERE id_don = ?");
    $already->execute([$idDon]);
    if ($already->fetchColumn() > 0) {
        header('Location:' . DOMAIN . 'medecin/tests.php?message=409');
if (!empty($_POST)) {
    
    if (isset($_POST['id_don']) && isset($_POST['confirm']) && isset($_POST['note'])) {
        try {
            
            $id_don = $_POST['id_don'];
            $confirm = $_POST['confirm'];
            $note = trim($_POST['note']);
            
            if ($id_don === false || $id_don <= 0) {
                header("Location:" . DOMAIN . "medecin/dons.php");
                exit;
            }
            
            if ($confirm === false || ($confirm != 0 && $confirm != 1)) {
                header("Location:" . DOMAIN . "medecin/dons.php");
                exit;
            }
            
            $stmt = $pdo->prepare("SELECT * FROM dons WHERE id_don = ?");
            $stmt->execute([$id_don]); 
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                header("Location:" . DOMAIN . "medecin/dons.php");
                exit;
            }
            
            $updateStmt = $pdo->prepare("
                UPDATE dons 
                SET statut = ?
                WHERE id_don = ?
            "); 
            
            $statut_test = ($confirm == 1) ? 'VALIDE' : 'REJETÉ';
            
            
            $insertStmt = $pdo->prepare("INSERT INTO `tests_don`(`id_don`,`date_test`, `est_conforme`, `notes_medecin`) VALUES (?,?, ?, ?)");
            $insertStmt->execute([$id_don,date("Y-m-d H:i:s"), $confirm, $note]);
            
            if($insertStmt->rowCount() > 0){
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
            } else {
                header("Location:" . DOMAIN . "medecin/?error=501");
                exit;
            }
            
        } catch (PDOException $e) {
            error_log("Database error in testHandler: " . $e->getMessage());
            header("Location:" . DOMAIN . "medecin/?error=500");
            exit;
        }
    } else {
        header("Location:" . DOMAIN . "medecin/test.php");
        exit;
    }

    $pdo->beginTransaction();

    $insert = $pdo->prepare(
        "INSERT INTO tests_don (id_don, date_test, est_conforme, notes_medecin)
         VALUES (?, NOW(), ?, ?)"
    );
    $insert->execute([$idDon, $isConforme, $note]);

    $newStatus = $isConforme === 1 ? 'VALIDE' : 'REJETÉ';
    $update    = $pdo->prepare("UPDATE dons SET statut = ? WHERE id_don = ?");
    $update->execute([$newStatus, $idDon]);

    $pdo->commit();

    header('Location:' . DOMAIN . 'medecin/tests.php?message=201');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Erreur testHandler: ' . $e->getMessage());
    header('Location:' . DOMAIN . 'medecin/tests.php?message=500');
    exit;
}
