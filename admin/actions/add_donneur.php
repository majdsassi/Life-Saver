<?php
require_once '../../utils/connection.php';
require_once '../includes/check_auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cin = htmlspecialchars($_POST['cin']);
  $nom = htmlspecialchars($_POST['nom']);
  $prenom = htmlspecialchars($_POST['prenom']);
  $date_naissance = htmlspecialchars($_POST['date_naissance']);
  $telephone = htmlspecialchars($_POST['telephone']);
  $adresse = htmlspecialchars($_POST['adresse']);
  $groupe = htmlspecialchars($_POST['groupe_sanguin']);

  // Insertion sécurisée via PDO
  $stmt = $pdo->prepare("INSERT INTO donneurs (cin, nom, prenom, date_naissance, telephone, adresse, groupe_sanguin)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([$cin, $nom, $prenom, $date_naissance, $telephone, $adresse, $groupe]);

  header("Location: ../controls/donneurs.php?message=201");
  exit();
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4">
  <h3>Ajouter un Donneur</h3>

  <form method="POST" class="mt-3">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label for="cin" class="form-label">CIN</label>
        <input type="text" id="cin" name="cin" class="form-control" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" id="prenom" name="prenom" class="form-control" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="date_naissance" class="form-label">Date de naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" id="telephone" name="telephone" class="form-control" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" id="adresse" name="adresse" class="form-control" required>
      </div>

      <div class="col-md-4 mb-3">
        <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
        <select id="groupe_sanguin" name="groupe_sanguin" class="form-select" required>
          <option value="">-- Choisir --</option>
          <option value="A+">A+</option>
          <option value="A-">A-</option>
          <option value="B+">B+</option>
          <option value="B-">B-</option>
          <option value="AB+">AB+</option>
          <option value="AB-">AB-</option>
          <option value="O+">O+</option>
          <option value="O-">O-</option>
        </select>
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-success">Ajouter</button>
      <a href="../donneurs.php" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>