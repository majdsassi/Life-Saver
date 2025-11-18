<?php 
session_start();
require_once '../config.php';
require_once '../utils/connection.php'; 
$messages = [
    "201" => "Don Changed Succesfully " ] ;
$errors = [
    "404" => "Don Not Found" ,
    "500" => "Server Error : Test Not inserted"
]
if(isset($_SESSION["user_id"])){
    if($_SESSION["user_role"] == "MEDECIN") {
         include 'includes/header.php'; 
         include "includes/sidebar.php"; ?>
         <div class="container-fluid mt-4">
    <h2 class="mb-4">Tableau de Bord - MEDECIN </h2>
    <?php 
     if(isset($_GET["error"])) {
                    echo '
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error:</strong> ' . htmlspecialchars($errors[$_GET["error"]]) . '
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
} 
if(isset($_GET["success"])) {
                    echo '
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Success :</strong> ' . htmlspecialchars($messages[$_GET["success"]]) . '
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
} 
    
    ?>
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-danger text-white">
            <strong>Dons</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>id Don</th>
                        <th>Date Don </th>
                        <th>Volume</th>
                        <th>id centre</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $pdo->prepare("SELECT * FROM dons WHERE `statut` = 'EN STOCK' AND `id_centre`= ? ");
                        $stmt->execute([$_SESSION['centre_id']]) ;
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['id_don']) . "</td>
                                    <td class=''>" . htmlspecialchars($row['date_don']) . "</td>
                                    <td class=''>" . htmlspecialchars($row['volume_ml']) . "</td>
                                    <td class=''>" . htmlspecialchars($row['id_centre']) . "</td>
                                    <td class=''><a href='/medecin/test.php?id_don=" . htmlspecialchars($row['id_don']) . "' class='btn btn-danger btn-sm'>
                    <i class='bi bi-box-arrow-right'></i> Edit 
                </a></td>
                                  </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted'>Aucune Dons EN STOCK</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
         <?php include 'includes/footer.php';
    }
    else{
        header("Location:" . DOMAIN . "login.php?error=403");
        exit; 
    }
}
else{
        header("Location:" . DOMAIN . "login.php?error=401");
        exit; 
}