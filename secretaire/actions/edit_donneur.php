<?php

    require_once __DIR__ . '/../../utils/connection.php';
    require_once __DIR__ . '/../includes/check_auth.php';
    require_once __DIR__ . '/../utils/helpers.php';

    $donneurId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($donneurId <= 0) {
        header('Location: ' . DOMAIN . 'secretaire/donneurs.php?error=missing_id');
        exit;
    }

    $donneur = get_donneur_by_id($donneurId);

    if (! $donneur) {
        header('Location: ' . DOMAIN . 'secretaire/donneurs.php?error=not_found');
        exit;
    }

    $errors     = [];
    $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cin            = trim($_POST['cin'] ?? '');
        $nom            = trim($_POST['nom'] ?? '');
        $prenom         = trim($_POST['prenom'] ?? '');
        $date_naissance = trim($_POST['date_naissance'] ?? '');
        $telephone      = trim($_POST['telephone'] ?? '');
        $adresse        = trim($_POST['adresse'] ?? '');
        $groupe         = trim($_POST['groupe_sanguin'] ?? '');

        if ($cin === '' || $nom === '' || $prenom === '' || $date_naissance === '' || $telephone === '' || $adresse === '' || $groupe === '') {
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        if (! $errors) {
            $stmt = $pdo->prepare("UPDATE donneurs SET cin = ?, nom = ?, prenom = ?, date_naissance = ?, telephone = ?, adresse = ?, groupe_sanguin = ? WHERE id_donneur = ?");
            $stmt->execute([$cin, $nom, $prenom, $date_naissance, $telephone, $adresse, $groupe, $donneurId]);

            header('Location: ' . DOMAIN . 'secretaire/donneurs.php?message=202');
            exit;
        }

        $donneur = array_merge($donneur, [
            'cin'            => $cin,
            'nom'            => $nom,
            'prenom'         => $prenom,
            'date_naissance' => $date_naissance,
            'telephone'      => $telephone,
            'adresse'        => $adresse,
            'groupe_sanguin' => $groupe,
        ]);
    }

    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<div class="container mt-4">
  <h3>Modifier un Donneur</h3>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <?php echo implode('<br>', array_map('e', $errors)); ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="mt-3">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label for="cin" class="form-label">CIN</label>
        <input type="text" id="cin" name="cin" class="form-control" value="<?php echo e($donneur['cin']); ?>" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?php echo e($donneur['nom']); ?>" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" id="prenom" name="prenom" class="form-control" value="<?php echo e($donneur['prenom']); ?>"
          required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="date_naissance" class="form-label">Date de naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" class="form-control"
          value="<?php echo e($donneur['date_naissance']); ?>" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" id="telephone" name="telephone" class="form-control"
          value="<?php echo e($donneur['telephone']); ?>" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" id="adresse" name="adresse" class="form-control"
          value="<?php echo e($donneur['adresse']); ?>" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
        <select id="groupe_sanguin" name="groupe_sanguin" class="form-select" required>
          <option value="">-- Choisir --</option>
          <?php foreach ($bloodTypes as $type): ?>
            <option value="<?php echo $type; ?>"<?php echo $donneur['groupe_sanguin'] === $type ? 'selected' : ''; ?>>
              <?php echo $type; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-success">Modifier</button>
      <a href="<?php echo DOMAIN . 'secretaire/donneurs.php'; ?>" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>