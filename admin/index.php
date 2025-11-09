<?php
    require_once './includes/check_auth.php';
    require_once '../utils/connection.php';
    $page_title = "Tableau de Bord";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Tableau de Bord - Administration</h2>

    <div class="row">
        <!-- donneurs -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Donneurs</h5>
                    <h2 class="text-primary">
                        <?php
                            $stmt = $pdo->query("SELECT COUNT(*) FROM donneurs");
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
                    <h2 class="text-success">
                        <?php
                            $stmt = $pdo->query("SELECT COUNT(*) FROM dons WHERE statut='VALIDE'");
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
                        $stmt = $pdo->query("SELECT groupe_sanguin, niveau_alerte FROM besoins WHERE niveau_alerte IN ('URGENT','CRITIQUE')");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                $color = $row['niveau_alerte'] === 'URGENT' ? 'text-danger' : 'text-warning';
                                echo "<tr>
                                    <td>{$row['groupe_sanguin']}</td>
                                    <td class='$color'><strong>{$row['niveau_alerte']}</strong></td>
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

<?php include 'includes/footer.php'; ?>
