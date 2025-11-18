<?php
    require_once __DIR__ . '/includes/check_auth.php';
    require_once __DIR__ . '/../utils/connection.php';
    require_once __DIR__ . '/utils/helpers.php';

    $besoins       = get_besoins();
    $niveauOptions = ['NORMAL', 'CRITIQUE', 'URGENT'];
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="mb-1">Gestion des besoins en stock</h3>
            <p class="text-muted mb-0">Configurez les seuils par groupe sanguin pour alimenter les alertes.</p>
        </div>
        <a href="<?php echo DOMAIN . 'admin/actions/add_besoin.php'; ?>" class="btn btn-primary">
            + Nouveau besoin
        </a>
    </div>

    <?php
        if (isset($_GET['message'])) {
            $alerts = [
                '201' => ['type' => 'success', 'text' => 'Besoin enregistré avec succès.'],
                '202' => ['type' => 'success', 'text' => 'Besoin mis à jour.'],
                '203' => ['type' => 'danger', 'text' => 'Besoin supprimé.'],
                '500' => ['type' => 'danger', 'text' => 'Erreur lors de l’opération.'],
            ];

            if (isset($alerts[$_GET['message']])) {
                $alert = $alerts[$_GET['message']];
                echo "<div class='alert alert-{$alert['type']} alert-dismissible fade show' role='alert'>
                    {$alert['text']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            }
        }
    ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <strong>Tableau des besoins</strong>
        </div>
        <div class="card-body p-0">
            <?php if ($besoins): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Groupe sanguin</th>
                                <th>Niveau d’alerte</th>
                                <th>Quantité cible (ml)</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($besoins as $besoin): ?>
                                <?php
                                    $badgeClass = match ($besoin['niveau_alerte']) {
                                        'URGENT'   => 'bg-danger',
                                        'CRITIQUE' => 'bg-warning text-dark',
                                        default    => 'bg-success',
                                    };
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($besoin['id_besoin']); ?></td>
                                    <td><?php echo htmlspecialchars($besoin['groupe_sanguin']); ?></td>
                                    <td><span class="badge                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($besoin['niveau_alerte']); ?></span></td>
                                    <td><?php echo htmlspecialchars($besoin['quantite_cible']); ?></td>
                                    <td class="text-end">
                                        <a href="<?php echo DOMAIN . 'admin/actions/edit_besoin.php?id=' . urlencode($besoin['id_besoin']); ?>" class="btn btn-sm btn-warning text-dark">Modifier</a>
                                        <a href="<?php echo DOMAIN . 'admin/actions/delete_besoin.php?id=' . urlencode($besoin['id_besoin']); ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Supprimer ce besoin ?');">
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
                        Aucun besoin configuré pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

