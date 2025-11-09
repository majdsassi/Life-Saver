<?php
require_once '../includes/check_auth.php';
require_once '../../utils/connection.php';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="container-fluid mt-4">
    <h3 class="mb-4">Gestion des Donneurs</h3>

    <a href="http://localhost/Life-Saver/admin/actions/add_donneur.php" class="btn btn-primary mb-3">
        + Nouveau Donneur
    </a>

    <?php
    if (isset($_GET['message'])) {
        $msg = $_GET['message'];

        switch ($msg) {
            case '201':
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Donneur ajouté avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                break;

            case '202':
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Donneur modifié avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                break;

            case '203':
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Donneur supprimé !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                break;

            default:
                // aucun message
                break;
        }
    }
    ?>


    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>CIN</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de Naissance</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Groupe Sanguin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM donneurs ORDER BY nom ASC");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['cin']) . "</td>
                        <td>" . htmlspecialchars($row['nom']) . "</td>
                        <td>" . htmlspecialchars($row['prenom']) . "</td>
                        <td>" . htmlspecialchars($row['date_naissance']) . "</td>
                        <td>" . htmlspecialchars($row['telephone']) . "</td>
                        <td>" . htmlspecialchars($row['adresse']) . "</td>
                        <td>" . htmlspecialchars($row['groupe_sanguin']) . "</td>
                        <td>
                            <a href='actions/edit_donneur.php?id=" . $row['id_donneur'] . "' class='btn btn-sm btn-warning'>Modifier</a>
                            <a href='actions/delete_donneur.php?id=" . $row['id_donneur'] . "' class='btn btn-sm btn-danger'>Supprimer</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>