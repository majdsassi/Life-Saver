<?php
    require_once __DIR__ . '/includes/check_auth.php';
    require_once __DIR__ . '/../utils/connection.php';
    require_once __DIR__ . '/utils/helpers.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<?php
    $medecins    = get_all_users('MEDECIN');
    $secretaires = get_all_users('SECRETAIRE');
?>

<div class="container-fluid mt-4">
  <h2 class="mb-4">Gestion Utilisateurs</h2>

  <div class="row">
      <div class="row">
        <!-- nb medcin -->
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm border-0">
            <div class="card-body text-center">
              <h5 class="card-title">Medecins</h5>
              <h2 class="text-danger">
                <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'MEDECIN'");
                    echo $stmt->fetchColumn();
                ?>
              </h2>
            </div>
          </div>
        </div>
        <!-- nb secretaire -->
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm border-0">
            <div class="card-body text-center">
              <h5 class="card-title">Secretaire</h5>
              <h2 class="text-info">
                <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'SECRETAIRE'");
                    echo $stmt->fetchColumn();
                ?>
              </h2>
            </div>
          </div>
        </div>
    </div>
  </div>

  <div class="row mb-5 container m-auto">
    <ul class="nav nav-pills d-flex justify-content-center w-100 my-custom-nav">
      <li class="nav-item custom-nav-item flex-fill text-center border rounded me-3"> <a class="nav-link active"
          data-bs-toggle="pill" href="#gestion_medecin">Gestion Medecin</a>
      </li>
      <li class="nav-item custom-nav-item flex-fill text-center border rounded ms-3"> <a class="nav-link" data-bs-toggle="pill"
          href="#gestion_secretaire">Gestion
          Secretaire</a>
      </li>
    </ul>
  </div>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="gestion_medecin">

      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Médecins</h4>
        <a href="<?php echo DOMAIN . 'admin/actions/add_user.php?role=MEDECIN'; ?>" class="btn btn-primary btn-sm">
          + Ajouter un Médecin
        </a>
      </div>


      <?php
          if (isset($_GET['message'])) {
              $msg = $_GET['message'];

              switch ($msg) {
                  case '201':
                      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Medecin ajouté avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                      break;

                  case '202':
                      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Medecin modifié avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                      break;

                  case '203':
                      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Medecin supprimé !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                      break;

                  default:
                      // aucun message
                      break;
              }
          }
      ?>

      <?php if ($medecins) {?>
        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th>Nom Medecin</th>
              <th>Role</th>
              <th>ID centre de collecte</th>
              <th>Nom centre de collecte</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
                foreach ($medecins as $row) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['nom_utilisateur']) . "</td>
                        <td>" . htmlspecialchars($row['role']) . "</td>
                        <td>" . htmlspecialchars($row['id_centre']) . "</td>
                        <td>" . htmlspecialchars($row['nom_centre'] ?? 'N/A') . "</td>
                        <td>
                            <a href='" . DOMAIN . "admin/actions/edit_user.php?id=" . $row['id_utilisateur'] . "' class='btn btn-sm btn-warning text-dark'>Modifier</a>
                            <a href='" . DOMAIN . "admin/actions/delete_user.php?id=" . $row['id_utilisateur'] . "' class='btn btn-sm btn-danger'>Supprimer</a>
                        </td>
                      </tr>";
                }
                ?>
          </tbody>
        </table>
      <?php } else {?>
        <div class="alert alert-info">Aucun médecin enregistré pour le moment.</div>
      <?php }?>

    </div>
    <div class="tab-pane fade" id="gestion_secretaire">

      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Secrétaires</h4>
        <a href="<?php echo DOMAIN . 'admin/actions/add_user.php?role=SECRETAIRE'; ?>" class="btn btn-primary btn-sm">
          + Ajouter une Secrétaire
        </a>
      </div>

    <?php
        if (isset($_GET['message'])) {
            $msg = $_GET['message'];

            switch ($msg) {
                case '201':
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Secretaire ajouté avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                    break;

                case '202':
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Secretaire modifié avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                    break;

                case '203':
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Secretaire supprimé !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                    break;

                default:
                    // aucun message
                    break;
            }
        }
    ?>
    <?php if ($secretaires) {?>
        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th>Nom Secrétaire</th>
              <th>Role</th>
              <th>ID centre de collecte</th>
              <th>Nom centre de collecte</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
                foreach ($secretaires as $row) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['nom_utilisateur']) . "</td>
                        <td>" . htmlspecialchars($row['role']) . "</td>
                        <td>" . htmlspecialchars($row['id_centre']) . "</td>
                        <td>" . htmlspecialchars($row['nom_centre'] ?? 'N/A') . "</td>
                        <td>
                            <a href='" . DOMAIN . "admin/actions/edit_user.php?id=" . $row['id_utilisateur'] . "' class='btn btn-sm btn-warning text-dark'>Modifier</a>
                            <a href='" . DOMAIN . "admin/actions/delete_user.php?id=" . $row['id_utilisateur'] . "' class='btn btn-sm btn-danger'>Supprimer</a>
                        </td>
                      </tr>";
                }
                ?>
          </tbody>
        </table>
      <?php } else {?>
        <div class="alert alert-info">Aucun secrétaire enregistré pour le moment.</div>
      <?php }?>
    </div>
  </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>