<?php

    require_once __DIR__ . '/../../utils/connection.php';
    require_once __DIR__ . '/../includes/check_auth.php';
    require_once __DIR__ . '/../utils/helpers.php';

    $userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($userId <= 0) {
        header('Location: ' . DOMAIN . 'admin/utilisateurs.php?error=missing_id');
        exit;
    }

    $user = get_user_by_id($userId);

    if (! $user) {
        header('Location: ' . DOMAIN . 'admin/utilisateurs.php?error=not_found');
        exit;
    }

    $centres = get_all_centres();
    $roles   = ['MEDECIN' => 'Médecin', 'SECRETAIRE' => 'Secrétaire'];
    $errors  = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_utilisateur = trim($_POST['nom_utilisateur'] ?? '');
        $role            = trim($_POST['role'] ?? '');
        $id_centre       = trim($_POST['id_centre'] ?? '');

        if ($nom_utilisateur === '' || $role === '' || $id_centre === '') {
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        if ($role && ! array_key_exists($role, $roles)) {
            $errors[] = 'Rôle invalide.';
        }

        $centreIds = array_column($centres, 'id_centre');
        if ($id_centre !== '' && ! in_array($id_centre, $centreIds)) {
            $errors[] = 'Centre sélectionné invalide.';
        }

        if (! $errors) {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom_utilisateur = ?, role = ?, id_centre = ? WHERE id_utilisateur = ?");
            $stmt->execute([$nom_utilisateur, $role, $id_centre, $userId]);

            header('Location: ' . DOMAIN . 'admin/utilisateurs.php?message=202');
            exit;
        }

        $user = array_merge($user, [
            'nom_utilisateur' => $nom_utilisateur,
            'role'            => $role,
            'id_centre'       => $id_centre,
        ]);
    }

    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<div class="container mt-4">
    <h3>Modifier un Utilisateur</h3>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php echo implode('<br>', array_map('e', $errors)); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom_utilisateur" class="form-label">Nom</label>
                <input type="text" id="nom_utilisateur" name="nom_utilisateur" class="form-control" value="<?php echo e($user['nom_utilisateur'] ?? ''); ?>" required>
            </div>

            <div class="col-md-3 mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($roles as $value => $label): ?>
                        <option value="<?php echo $value; ?>"<?php echo($user['role'] === $value) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="id_centre" class="form-label">Centre</label>
                <select id="id_centre" name="id_centre" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($centres as $centre): ?>
                        <option value="<?php echo e($centre['id_centre']); ?>"<?php echo($user['id_centre'] == $centre['id_centre']) ? 'selected' : ''; ?>>
                            <?php echo e($centre['nom_centre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Modifier</button>
            <a href="<?php echo DOMAIN . 'admin/utilisateurs.php'; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

