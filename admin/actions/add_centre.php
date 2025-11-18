<?php
    require_once __DIR__ . '/../../utils/connection.php';
    require_once __DIR__ . '/../includes/check_auth.php';

    $errors   = [];
    $formData = [
        'nom_centre' => '',
        'ville'      => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData['nom_centre'] = trim($_POST['nom_centre'] ?? '');
        $formData['ville']      = trim($_POST['ville'] ?? '');

        if ($formData['nom_centre'] === '' || $formData['ville'] === '') {
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        if (! $errors) {
            $stmt = $pdo->prepare("INSERT INTO centres_collecte (nom_centre, ville) VALUES (?, ?)");
            $stmt->execute([
                $formData['nom_centre'],
                $formData['ville'],
            ]);

            header('Location: ' . DOMAIN . 'admin/centres.php?message=201');
            exit;
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
    <h3>Ajouter un Centre</h3>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php echo implode('<br>', array_map('e', $errors)); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom_centre" class="form-label">Nom du centre</label>
                <input type="text" id="nom_centre" name="nom_centre" class="form-control" value="<?php echo e($formData['nom_centre']); ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="ville" class="form-label">Ville</label>
                <input type="text" id="ville" name="ville" class="form-control" value="<?php echo e($formData['ville']); ?>" required>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="<?php echo DOMAIN . 'admin/centres.php'; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

