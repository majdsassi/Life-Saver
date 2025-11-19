<?php
    session_start();
    require_once '../config.php';
    require_once '../utils/connection.php';

    if (! isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'MEDECIN') {
        header('Location:' . DOMAIN . 'login.php?error=401');
        exit;
    }

    $centreId = $_SESSION['centre_id'] ?? null;

    if (! $centreId) {
        header('Location:' . DOMAIN . 'login.php?error=403');
        exit;
    }

    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/includes/sidebar.php';

    $pendingStmt = $pdo->prepare(
        "SELECT d.id_don,
            d.date_don,
            d.volume_ml,
            dn.nom,
            dn.prenom,
            dn.groupe_sanguin
     FROM dons d
     INNER JOIN donneurs dn ON d.id_donneur = dn.id_donneur
     WHERE d.statut = 'EN STOCK' AND d.id_centre = ?
     ORDER BY d.date_don ASC"
    );
    $pendingStmt->execute([$centreId]);
    $pendingDons = $pendingStmt->fetchAll(PDO::FETCH_ASSOC);

    $historyStmt = $pdo->prepare(
        "SELECT t.date_test,
            t.est_conforme,
            t.notes_medecin,
            d.id_don,
            d.date_don,
            d.volume_ml,
            dn.nom,
            dn.prenom
     FROM tests_don t
     INNER JOIN dons d ON t.id_don = d.id_don
     INNER JOIN donneurs dn ON d.id_donneur = dn.id_donneur
     WHERE d.id_centre = ?
     ORDER BY t.date_test DESC"
    );
    $historyStmt->execute([$centreId]);
    $history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);

    $alerts = [
        '201' => ['type' => 'success', 'text' => 'Résultat du test enregistré avec succès.'],
        '409' => ['type' => 'warning', 'text' => 'Ce don a déjà été testé.'],
        '404' => ['type' => 'danger', 'text' => 'Don introuvable ou non autorisé.'],
        '400' => ['type' => 'danger', 'text' => 'Données de formulaire invalides.'],
    ];

    $message = isset($_GET['message'], $alerts[$_GET['message']]) ? $alerts[$_GET['message']] : null;
?>

<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="mb-1">Validation des Tests</h3>
            <p class="text-muted mb-0">Suivi des poches en attente et historique des validations.</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message['text']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-danger text-white">
            <strong>Dons « EN STOCK » de votre centre</strong>
        </div>
        <div class="card-body p-0">
            <?php if ($pendingDons): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Donneur</th>
                                <th>Groupe</th>
                                <th>Date du don</th>
                                <th>Volume (ml)</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingDons as $don): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($don['id_don']); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom']) . ' ' . htmlspecialchars($don['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($don['groupe_sanguin']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($don['date_don']))); ?></td>
                                    <td><?php echo htmlspecialchars($don['volume_ml']); ?></td>
                                    <td class="text-end">
                                        <a href="<?php echo DOMAIN . 'medecin/test.php?id_don=' . urlencode($don['id_don']); ?>" class="btn btn-sm btn-outline-light bg-danger text-white">
                                            Valider le test
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
                        Aucun don en attente de test pour votre centre.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white">
            <strong>Historique des tests</strong>
        </div>
        <div class="card-body p-0">
            <?php if ($history): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Donneur</th>
                                <th>Date du don</th>
                                <th>Date du test</th>
                                <th>Résultat</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id_don']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($row['date_don']))); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($row['date_test']))); ?></td>
                                    <td>
                                        <?php if ($row['est_conforme']): ?>
                                            <span class="badge bg-success">Conforme</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejeté</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['notes_medecin'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4">
                    <div class="alert alert-secondary mb-0">
                        Aucun test enregistré pour votre centre.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
