<?php
$allowedRoles = ['ADMIN', 'SECRETAIRE'];
require_once __DIR__ . '/includes/check_auth.php';
require_once __DIR__ . '/../utils/connection.php';
require_once __DIR__ . '/utils/helpers.php';

$statuses       = ['EN STOCK', 'VALIDE', 'REJETÉ', 'UTILISÉ'];
$selectedStatus = isset($_GET['statut']) && in_array($_GET['statut'], $statuses, true)
    ? $_GET['statut']
    : null;

$dons = get_dons($selectedStatus);
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="mb-1">Gestion des Dons</h3>
            <p class="text-muted mb-0">Création et suivi des poches de sang collectées.</p>
        </div>
        <a href="<?php echo DOMAIN . 'secretaire/actions/add_don.php'; ?>" class="btn btn-primary">
            + Enregistrer un Don
        </a>
    </div>

    <?php
    if (isset($_GET['message'])) {
        $msg    = $_GET['message'];
        $alerts = [
            '201' => ['type' => 'success', 'text' => 'Don enregistré avec succès.'],
            '400' => ['type' => 'danger', 'text' => 'Les informations fournies sont invalides.'],
            '500' => ['type' => 'danger', 'text' => 'Erreur serveur lors de l\'enregistrement du don.'],
        ];

        if (isset($alerts[$msg])) {
            $alert = $alerts[$msg];
            echo "<div class='alert alert-{$alert['type']} alert-dismissible fade show' role='alert'>
                    {$alert['text']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
        }
    }
    ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-danger text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <strong>Liste des dons</strong>
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="statut" class="mb-0 small">Filtrer par statut :</label>
                <select id="statut" name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo $status; ?>" <?php echo $selectedStatus === $status ? ' selected' : ''; ?>>
                            <?php echo $status; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="card-body p-0">
            <?php if ($dons): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Donneur</th>
                                <th>Centre</th>
                                <th>Date</th>
                                <th>Volume (ml)</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dons as $don): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($don['id_don']); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom']) . ' ' . htmlspecialchars($don['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom_centre'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($don['date_don']))); ?></td>
                                    <td><?php echo htmlspecialchars($don['volume_ml']); ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match ($don['statut']) {
                                            'EN STOCK' => 'stock',
                                            'VALIDE'   => 'valid',
                                            'REJETÉ'   => 'reject',
                                            'UTILISÉ'  => 'used',
                                            default    => ''
                                        };
                                        ?>
                                        <span class="badge-status<?php echo $badgeClass; ?>"><?php echo htmlspecialchars($don['statut']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4">
                    <div class="alert alert-info mb-0">
                        Aucun don ne correspond au filtre sélectionné.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>