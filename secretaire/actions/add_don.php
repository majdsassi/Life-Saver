<?php
    $allowedRoles = ['ADMIN', 'SECRETAIRE'];
    require_once __DIR__ . '/../../utils/connection.php';
    require_once __DIR__ . '/../includes/check_auth.php';
    require_once __DIR__ . '/../utils/helpers.php';

    $donneurs = get_all_donneurs();
    $centres  = get_all_centres(true);
    $errors   = [];
    $formData = [
        'id_donneur' => '',
        'id_centre'  => '',
        'date_don'   => date('Y-m-d\TH:i'),
        'volume_ml'  => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData['id_donneur'] = trim($_POST['id_donneur'] ?? '');
        $formData['id_centre']  = trim($_POST['id_centre'] ?? '');
        $formData['date_don']   = trim($_POST['date_don'] ?? '');
        $formData['volume_ml']  = trim($_POST['volume_ml'] ?? '');

        if ($formData['id_donneur'] === '' || $formData['id_centre'] === '' || $formData['date_don'] === '' || $formData['volume_ml'] === '') {
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        $donneur = null;
        if ($formData['id_donneur'] !== '') {
            $donneur = get_donneur_by_id($formData['id_donneur']);
            if (! $donneur) {
                $errors[] = 'Donneur sélectionné invalide.';
            }
        }

        $centreIds = array_column($centres, 'id_centre');
        if ($formData['id_centre'] !== '' && ! in_array($formData['id_centre'], $centreIds)) {
            $errors[] = 'Centre sélectionné invalide.';
        }

        $dateDon = null;
        if ($formData['date_don'] !== '') {
            $dateDon = DateTime::createFromFormat('Y-m-d\TH:i', $formData['date_don']);
            if (! $dateDon) {
                $errors[] = 'Format de date invalide.';
            }
        }

        if ($formData['volume_ml'] !== '' && (! ctype_digit($formData['volume_ml']) || (int) $formData['volume_ml'] <= 0)) {
            $errors[] = 'Le volume doit être un entier positif.';
        }

        if (! $errors && $dateDon instanceof DateTime) {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO dons (date_don, volume_ml, statut, id_donneur, id_centre)
                 VALUES (?, ?, 'EN STOCK', ?, ?)"
                );
                $stmt->execute([
                    $dateDon->format('Y-m-d H:i:s'),
                    (int) $formData['volume_ml'],
                    $formData['id_donneur'],
                    $formData['id_centre'],
                ]);

                header('Location: ' . DOMAIN . 'secretaire/dons.php?message=201');
                exit;
            } catch (PDOException $e) {
                error_log('Erreur ajout don : ' . $e->getMessage());
                $errors[] = 'Une erreur est survenue lors de l’enregistrement.';
            }
        }
    }

    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<div class="container mt-4">
    <h3>Enregistrer un nouveau don</h3>
    <p class="text-muted">Saisie réalisée par l’administrateur ou la secrétaire.</p>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php echo implode('<br>', array_map('e', $errors)); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_donneur" class="form-label">Donneur</label>
                <select id="id_donneur" name="id_donneur" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($donneurs as $donneur): ?>
                        <option value="<?php echo e($donneur['id_donneur']); ?>"<?php echo $formData['id_donneur'] == $donneur['id_donneur'] ? ' selected' : ''; ?>>
                            <?php echo e($donneur['nom'] . ' ' . $donneur['prenom'] . ' (' . $donneur['groupe_sanguin'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="date_don" class="form-label">Date du don</label>
                <input type="datetime-local" id="date_don" name="date_don" class="form-control" value="<?php echo e($formData['date_don']); ?>" required>
            </div>

            <div class="col-md-3 mb-3">
                <label for="volume_ml" class="form-label">Volume collecté (ml)</label>
                <input type="number" id="volume_ml" name="volume_ml" class="form-control" min="1" step="50" value="<?php echo e($formData['volume_ml']); ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="id_centre" class="form-label">Centre de collecte</label>
                <select id="id_centre" name="id_centre" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($centres as $centre): ?>
                        <option value="<?php echo e($centre['id_centre']); ?>"<?php echo $formData['id_centre'] == $centre['id_centre'] ? ' selected' : ''; ?>>
                            <?php echo e($centre['nom_centre'] . (! empty($centre['ville']) ? ' - ' . $centre['ville'] : '')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="<?php echo DOMAIN . 'secretaire/dons.php'; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

