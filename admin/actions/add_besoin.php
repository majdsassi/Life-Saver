<?php
    require_once __DIR__ . '/../../utils/connection.php';
    require_once __DIR__ . '/../includes/check_auth.php';

    $groupeOptions = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    $niveauOptions = ['NORMAL', 'CRITIQUE', 'URGENT'];

    $errors   = [];
    $formData = [
        'groupe_sanguin' => '',
        'niveau_alerte'  => 'NORMAL',
        'quantite_cible' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData['groupe_sanguin'] = $_POST['groupe_sanguin'] ?? '';
        $formData['niveau_alerte']  = $_POST['niveau_alerte'] ?? '';
        $formData['quantite_cible'] = trim($_POST['quantite_cible'] ?? '');

        if (! in_array($formData['groupe_sanguin'], $groupeOptions, true)) {
            $errors[] = 'Groupe sanguin invalide.';
        }

        if (! in_array($formData['niveau_alerte'], $niveauOptions, true)) {
            $errors[] = 'Niveau d’alerte invalide.';
        }

        if ($formData['quantite_cible'] === '' || ! ctype_digit($formData['quantite_cible'])) {
            $errors[] = 'La quantité cible doit être un entier positif.';
        }

        if (! $errors) {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO besoins (groupe_sanguin, niveau_alerte, quantite_cible)
                 VALUES (?, ?, ?)"
                );
                $stmt->execute([
                    $formData['groupe_sanguin'],
                    $formData['niveau_alerte'],
                    (int) $formData['quantite_cible'],
                ]);

                header('Location: ' . DOMAIN . 'admin/besoins.php?message=201');
                exit;
            } catch (PDOException $e) {
                error_log('Erreur ajout besoin : ' . $e->getMessage());
                $errors[] = 'Impossible d’enregistrer ce besoin.';
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
    <h3>Ajouter un besoin</h3>
    <p class="text-muted">Définissez un seuil pour un groupe sanguin donné.</p>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php echo implode('<br>', array_map('e', $errors)); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                <select id="groupe_sanguin" name="groupe_sanguin" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($groupeOptions as $groupe): ?>
                        <option value="<?php echo $groupe; ?>"<?php echo $formData['groupe_sanguin'] === $groupe ? ' selected' : ''; ?>>
                            <?php echo $groupe; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="niveau_alerte" class="form-label">Niveau d’alerte</label>
                <select id="niveau_alerte" name="niveau_alerte" class="form-select" required>
                    <?php foreach ($niveauOptions as $niveau): ?>
                        <option value="<?php echo $niveau; ?>"<?php echo $formData['niveau_alerte'] === $niveau ? ' selected' : ''; ?>>
                            <?php echo $niveau; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="quantite_cible" class="form-label">Quantité cible (ml)</label>
                <input type="number" id="quantite_cible" name="quantite_cible" class="form-control" min="1" step="50" value="<?php echo e($formData['quantite_cible']); ?>" required>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="<?php echo DOMAIN . 'admin/besoins.php'; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

