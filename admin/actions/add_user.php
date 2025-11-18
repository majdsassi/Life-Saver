<?php

    require_once __DIR__ . '/../../utils/connection.php';
    require_once __DIR__ . '/../includes/check_auth.php';
    require_once __DIR__ . '/../utils/helpers.php';

    $roles   = ['MEDECIN' => 'Médecin', 'SECRETAIRE' => 'Secrétaire'];
    $centres = get_all_centres();
    $errors  = [];

    $formData = [
        'nom_utilisateur' => '',
        'role'            => (isset($_GET['role']) && array_key_exists($_GET['role'], $roles)) ? $_GET['role'] : '',
        'id_centre'       => '',
        'mot_de_passe'    => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData['nom_utilisateur'] = trim($_POST['nom_utilisateur'] ?? '');
        $formData['role']            = trim($_POST['role'] ?? '');
        $formData['id_centre']       = trim($_POST['id_centre'] ?? '');
        $formData['mot_de_passe']    = $_POST['mot_de_passe'] ?? '';

        if ($formData['nom_utilisateur'] === '' || $formData['role'] === '' || $formData['id_centre'] === '' || $formData['mot_de_passe'] === '') {
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        if ($formData['role'] && ! array_key_exists($formData['role'], $roles)) {
            $errors[] = 'Rôle invalide.';
        }

        $centreIds = array_column($centres, 'id_centre');
        if ($formData['id_centre'] !== '' && ! in_array($formData['id_centre'], $centreIds)) {
            $errors[] = 'Centre sélectionné invalide.';
        }

        if (! $errors) {
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, role, id_centre, mot_de_passe) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $formData['nom_utilisateur'],
                $formData['role'],
                $formData['id_centre'],
                password_hash($formData['mot_de_passe'], PASSWORD_BCRYPT),
            ]);

            header('Location: ' . DOMAIN . 'admin/utilisateurs.php?message=201');
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
  <h3>Ajouter un Utilisateur</h3>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <?php echo implode('<br>', array_map('e', $errors)); ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="mt-3">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="nom_utilisateur" class="form-label">Nom</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" class="form-control" value="<?php echo e($formData['nom_utilisateur']); ?>" required>
      </div>

      <div class="col-md-3 mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select id="role" name="role" class="form-select" required>
          <option value="">-- Choisir --</option>
          <?php foreach ($roles as $value => $label): ?>
            <option value="<?php echo $value; ?>"<?php echo($formData['role'] === $value) ? 'selected' : ''; ?>>
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
            <option value="<?php echo e($centre['id_centre']); ?>"<?php echo($formData['id_centre'] == $centre['id_centre']) ? 'selected' : ''; ?>>
              <?php echo e($centre['nom_centre']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label for="mot_de_passe" class="form-label">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-success">Ajouter</button>
      <a href="<?php echo DOMAIN . 'admin/utilisateurs.php'; ?>" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

