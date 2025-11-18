<?php
    if (! defined('DOMAIN')) {
        require_once __DIR__ . '/../../config.php';
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title ?? "Administration"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo DOMAIN . 'styles/theme.css'; ?>">
</head>
<body class="app-theme app-admin">
<nav class="navbar navbar-expand-lg app-navbar shadow-sm sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="<?php echo DOMAIN . 'admin/index.php'; ?>">
                <i class="bi bi-dropbox"></i>
                Life Saver Admin
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto align-items-center gap-3">
                <li class="nav-item text-white-50 small d-none d-md-block">
                    <?php echo htmlspecialchars($_SESSION['user_role'] ?? 'ADMIN'); ?>
                </li>
                <li class="nav-item">
                    <a href="<?php echo DOMAIN . 'admin/utils/logout.php'; ?>" class="btn btn-outline-light btn-sm btn-app">
                        <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
