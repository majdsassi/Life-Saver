<?php
    session_start();
    require_once '../config.php';
    require_once '../utils/connection.php';

    if (! isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'MEDECIN') {
        header('Location:' . DOMAIN . 'login.php?error=unauthorized');
        exit;
    }

    if (! isset($_GET['id_don']) || ! ctype_digit($_GET['id_don'])) {
        header('Location:' . DOMAIN . 'medecin/tests.php?message=404');
        exit;
    }

    $centreId = $_SESSION['centre_id'] ?? null;
    $idDon    = (int) $_GET['id_don'];

    try {
        $stmt = $pdo->prepare(
            "SELECT d.*, dn.nom, dn.prenom, dn.groupe_sanguin, dn.cin, dn.telephone, dn.adresse
         FROM dons d
         INNER JOIN donneurs dn ON d.id_donneur = dn.id_donneur
         WHERE d.id_don = ? AND d.id_centre = ?"
        );
        $stmt->execute([$idDon, $centreId]);
        $don = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $don || $don['statut'] !== 'EN STOCK') {
            header('Location:' . DOMAIN . 'medecin/tests.php?message=404');
            exit;
        }

        $alreadyTested = $pdo->prepare("SELECT COUNT(*) FROM tests_don WHERE id_don = ?");
        $alreadyTested->execute([$idDon]);
        if ($alreadyTested->fetchColumn() > 0) {
            header('Location:' . DOMAIN . 'medecin/tests.php?message=409');
            exit;
        }
    } catch (PDOException $e) {
        error_log('Erreur chargement don: ' . $e->getMessage());
        header('Location:' . DOMAIN . 'medecin/tests.php?message=400');
        exit;
    }

    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/includes/sidebar.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Validation du Don #<?php echo htmlspecialchars($don['id_don']); ?></h2>
        <a href="<?php echo DOMAIN . 'medecin/tests.php'; ?>" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-danger text-white">
            <strong>Informations sur le don</strong>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-4">
                    <small class="text-muted d-block">Donneur</small>
                    <strong><?php echo htmlspecialchars($don['nom'] . ' ' . $don['prenom']); ?></strong>
                    <div class="text-muted"><?php echo htmlspecialchars($don['groupe_sanguin']); ?></div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Cin / Téléphone</small>
                    <strong><?php echo htmlspecialchars($don['cin']); ?></strong>
                    <div class="text-muted"><?php echo htmlspecialchars($don['telephone']); ?></div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Adresse</small>
                    <strong><?php echo htmlspecialchars($don['adresse']); ?></strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Date du don</small>
                    <strong><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($don['date_don']))); ?></strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Volume collecté</small>
                    <strong><?php echo htmlspecialchars($don['volume_ml']); ?> ml</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Statut actuel</small>
                    <span class="badge bg-secondary">EN STOCK</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <strong>Saisir les résultats du test</strong>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo DOMAIN . 'handlers/testHandler.php'; ?>">
                <input type="hidden" name="id_don" value="<?php echo htmlspecialchars($idDon); ?>">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="confirm" class="form-label">Résultat</label>
                        <select class="form-select" id="confirm" name="confirm" required>
                            <option value="">-- Choisir --</option>
                            <option value="1">Conforme</option>
                            <option value="0">Rejeté</option>
                        </select>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="note" class="form-label">Note / observation</label>
                        <textarea class="form-control" id="note" name="note" rows="4" placeholder="Observations médicales" required></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo DOMAIN . 'medecin/tests.php'; ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Enregistrer le résultat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>