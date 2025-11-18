<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../includes/check_auth.php';
require_once __DIR__ . '/../utils/helpers.php';

if (! isset($_GET['id']) || ! ctype_digit($_GET['id'])) {
    header('Location: ' . DOMAIN . 'admin/transfusions.php');
    exit;
}

$donId = (int) $_GET['id'];
$don = get_don_by_id($donId);

if (! $don || $don['statut'] !== 'VALIDE') {
    header('Location: ' . DOMAIN . 'admin/transfusions.php?message=302');
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM transfusions WHERE id_don = ?");
$stmt->execute([$donId]);
if ($stmt->fetchColumn() > 0) {
    header('Location: ' . DOMAIN . 'admin/transfusions.php?message=302');
    exit;
}

$errors = [];
$formData = [
    'hopital_recepteur' => '',
    'date_transfusion' => date('Y-m-d\TH:i'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['hopital_recepteur'] = trim($_POST['hopital_recepteur'] ?? '');
    $formData['date_transfusion'] = trim($_POST['date_transfusion'] ?? '');

    if ($formData['hopital_recepteur'] === '' || $formData['date_transfusion'] === '') {
        $errors[] = 'Tous les champs sont obligatoires.';
    }

    $dateTransfusion = null;
    if ($formData['date_transfusion'] !== '') {
        $dateTransfusion = DateTime::createFromFormat('Y-m-d\TH:i', $formData['date_transfusion']);
        if (! $dateTransfusion) {
            $errors[] = 'Format de date invalide.';
        }
    }

    if (! $errors && $dateTransfusion instanceof DateTime) {
        try {
            $pdo->beginTransaction();

            $insert = $pdo->prepare(
                "INSERT INTO transfusions (id_don, hopital_recepteur, date_transfusion)
                 VALUES (?, ?, ?)"
            );
            $insert->execute([
                $donId,
                $formData['hopital_recepteur'],
                $dateTransfusion->format('Y-m-d H:i:s'),
            ]);

            $update = $pdo->prepare("UPDATE dons SET statut = 'UTILISÉ' WHERE id_don = ?");
            $update->execute([$donId]);

            $pdo->commit();

            header('Location: ' . DOMAIN . 'admin/transfusions.php?message=301');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Erreur ajout transfusion : ' . $e->getMessage());
            $errors[] = 'Une erreur est survenue durant l’enregistrement.';
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
    <h3>Enregistrer une transfusion</h3>
    <p class="text-muted">Don n° <?php echo htmlspecialchars($don['id_don']); ?> – <?php echo htmlspecialchars($don['nom'] . ' ' . $don['prenom']); ?></p>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php echo implode('<br>', array_map('e', $errors)); ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Date du don :</strong><br>
                    <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($don['date_don']))); ?>
                </div>
                <div class="col-md-4">
                    <strong>Centre :</strong><br>
                    <?php echo htmlspecialchars($don['nom_centre'] ?? 'N/A'); ?>
                </div>
                <div class="col-md-4">
                    <strong>Volume :</strong><br>
                    <?php echo htmlspecialchars($don['volume_ml']); ?> ml
                </div>
            </div>
        </div>
    </div>

    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="hopital_recepteur" class="form-label">Hôpital receveur</label>
                <input type="text" id="hopital_recepteur" name="hopital_recepteur" class="form-control" value="<?php echo e($formData['hopital_recepteur']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="date_transfusion" class="form-label">Date de transfusion</label>
                <input type="datetime-local" id="date_transfusion" name="date_transfusion" class="form-control" value="<?php echo e($formData['date_transfusion']); ?>" required>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Valider</button>
            <a href="<?php echo DOMAIN . 'admin/transfusions.php'; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

