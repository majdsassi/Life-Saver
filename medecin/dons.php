<?php 
session_start();
require_once '../config.php';
require_once '../utils/connection.php'; 
if(isset($_SESSION["user_id"])){
    if($_SESSION["user_role"] == "MEDECIN") {
         include 'includes/header.php'; 
         include "includes/sidebar.php"; ?>
         <div class="container-fluid mt-4">
    <h2 class="mb-4">Tableau de Bord - MEDECIN </h2>
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
                        $stmt = $pdo->query("SELECT * FROM dons WHERE `statut` = 'EN STOCK'");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['id_don'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td class=''>" . htmlspecialchars($row['date_don'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td class=''>" . htmlspecialchars($row['volume_ml'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td class=''>" . htmlspecialchars($row['id_centre'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td class=''><a href='/medecin/test.php?id_don=" . htmlspecialchars($row['id_don'], ENT_QUOTES, 'UTF-8') . "' class='btn btn-outline-light btn-sm'>
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