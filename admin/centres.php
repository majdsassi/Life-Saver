<?php
    require_once __DIR__ . '/includes/check_auth.php';
    require_once __DIR__ . '/../utils/connection.php';
    require_once __DIR__ . '/utils/helpers.php';

    $centres = get_all_centres(true);
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h3 class="mb-0">Gestion des Centres de Collecte</h3>
        <a href="<?php echo DOMAIN . 'admin/actions/add_centre.php'; ?>" class="btn btn-primary">
            + Nouveau Centre
        </a>
    </div>

    <?php
        if (isset($_GET['message'])) {
            $msg = $_GET['message'];

            switch ($msg) {
                case '201':
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Centre ajouté avec succès !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    break;
                case '202':
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Centre modifié avec succès !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    break;
                case '203':
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Centre supprimé !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    break;
                case '500':
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Impossible de supprimer ce centre (utilisé ailleurs).
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    break;
                default:
                    break;
            }
        }
    ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <strong>Liste des centres</strong>
        </div>
        <div class="card-body p-0">
            <?php if ($centres): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nom du centre</th>
                                <th>Ville</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($centres as $centre): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($centre['id_centre']); ?></td>
                                    <td><?php echo htmlspecialchars($centre['nom_centre']); ?></td>
                                    <td><?php echo htmlspecialchars($centre['ville'] ?? ''); ?></td>
                                    <td class="text-end">
                                        <a href="<?php echo DOMAIN . 'admin/actions/edit_centre.php?id=' . urlencode($centre['id_centre']); ?>" class="btn btn-sm btn-warning text-dark">Modifier</a>
                                        <a href="<?php echo DOMAIN . 'admin/actions/delete_centre.php?id=' . urlencode($centre['id_centre']); ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Confirmer la suppression de ce centre ?');">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4">
                    <div class="alert alert-info mb-0">
                        Aucun centre enregistré pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

