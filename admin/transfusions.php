<?php
    require_once __DIR__ . '/includes/check_auth.php';
    require_once __DIR__ . '/../utils/connection.php';
    require_once __DIR__ . '/utils/helpers.php';

    $pending = get_pending_transfusions();
    $history = get_transfusions_history();
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="mb-1">Transfusions</h3>
            <p class="text-muted mb-0">Traçabilité de l’utilisation finale des poches validées.</p>
        </div>
    </div>

    <?php
        if (isset($_GET['message'])) {
            $msg    = $_GET['message'];
            $alerts = [
                '301' => ['type' => 'success', 'text' => 'Transfusion enregistrée et don marqué comme utilisé.'],
                '302' => ['type' => 'danger', 'text' => 'Impossible d’enregistrer la transfusion (don déjà utilisé).'],
                '500' => ['type' => 'danger', 'text' => 'Erreur serveur pendant l’opération.'],
            ];

            if (isset($alerts[$msg])) {
                $alert = $alerts[$msg];
                echo "<div class='alert alert-{$alert['type']} alert-dismissible fade show' role='alert'>
                    {$alert['text']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            }
        }
    ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <strong>Dons validés en attente d’utilisation</strong>
            <span class="badge bg-light text-success"><?php echo count($pending); ?> prêts</span>
        </div>
        <div class="card-body p-0">
            <?php if ($pending): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Donneur</th>
                                <th>Centre</th>
                                <th>Date du don</th>
                                <th>Volume (ml)</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending as $don): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($don['id_don']); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom']) . ' ' . htmlspecialchars($don['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom_centre'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($don['date_don']))); ?></td>
                                    <td><?php echo htmlspecialchars($don['volume_ml']); ?></td>
                                    <td class="text-end">
                                        <a href="<?php echo DOMAIN . 'admin/actions/add_transfusion.php?id=' . urlencode($don['id_don']); ?>" class="btn btn-sm btn-success">
                                            Enregistrer l’utilisation
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
                        Aucun don validé en attente de transfusion.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <strong>Historique des transfusions</strong>
        </div>
        <div class="card-body p-0">
            <?php if ($history): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Donneur</th>
                                <th>Centre</th>
                                <th>Hôpital receveur</th>
                                <th>Date transfusion</th>
                                <th>Volume (ml)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['id_don']); ?></td>
                                    <td><?php echo htmlspecialchars($record['nom']) . ' ' . htmlspecialchars($record['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($record['nom_centre'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($record['hopital_recepteur']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($record['date_transfusion']))); ?></td>
                                    <td><?php echo htmlspecialchars($record['volume_ml']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4">
                    <div class="alert alert-secondary mb-0">
                        Aucune transfusion enregistrée pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

