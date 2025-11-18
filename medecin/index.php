<?php 
session_start();
require_once '../config.php';
require_once '../utils/connection.php' ; 
if(isset($_SESSION["user_id"])){
    if($_SESSION["user_role"] == "MEDECIN") {
         include 'includes/header.php'; 
         include "includes/sidebar.php" ?>
         <div class="container-fluid mt-4">
    <h2 class="mb-4">Tableau de Bord - MEDECIN </h2>

    <div class="row">
        <!-- donneurs -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Donneurs</h5> 
                    <i class="bi-people"></i>
                    <h2 class="text-primary">
                        <?php
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM donneurs AND `id_centre`= ?");
                            $stmt->execute([$_POST['centre_id']]) ;
                            echo $stmt->fetchColumn();
                        ?>
                    </h2>
                </div>
            </div>
        </div>

        <!-- dons valides -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Dons valides</h5>
                    <i class="bi-check-circle"></i>
                    <h2 class="text-success">
                        <?php
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM dons WHERE statut='VALIDE'  AND `id_centre`= ?");
                            $stmt->execute([$_POST['centre_id']]) ;
                            echo $stmt->fetchColumn();
                        ?>
                    </h2>
                </div>
            </div>
        </div>

        <!-- centres -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Centres</h5>
                    <h2 class="text-warning">
                        <?php
                            $stmt = $pdo->query("SELECT COUNT(*) FROM centres_collecte");
                            echo $stmt->fetchColumn();
                        ?>
                    </h2>
                </div>
            </div>
        </div>

        <!-- dons en stock -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">En stock</h5>
                    <h2 class="text-danger">
                        <?php
                            $stmt = $pdo->query("SELECT COUNT(*) FROM dons WHERE statut='EN STOCK'");
                            echo $stmt->fetchColumn();
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-danger text-white">
            <strong>Alertes Stock Critique</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Groupe Sanguin</th>
                        <th>Niveau d’Alerte</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $pdo->query("SELECT groupe_sanguin, niveau_alerte , quantite_cible FROM besoins WHERE niveau_alerte IN ('URGENT','CRITIQUE')");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                $color = $row['niveau_alerte'] === 'URGENT' ? 'text-danger' : 'text-warning';
                                echo "<tr>
                                    <td>{$row['groupe_sanguin']}</td>
                                    <td class='$color'><strong>{$row['niveau_alerte']}</strong></td> 
                                    <td>{$row['quantite_cible']}ML</td>

                                  </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center text-muted'>Aucune alerte</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                    </body>
         <?php include 'includes/footer.php';


    }
    else{
        header("Location:".DOMAIN."login.php?error=403");
        exit ; 
    }
}
else{
        header("Location:".DOMAIN."login.php?error=401");
        exit ; 
}