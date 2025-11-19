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
    <title><?php echo $page_title ?? "Gestion"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo DOMAIN . 'styles/theme.css'; ?>">
</head>

<body class="app-theme app-admin">
    <nav class="navbar navbar-expand-lg app-navbar shadow-sm sticky-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between w-100">
                <a class="navbar-brand" href="<?php echo DOMAIN . 'secretaire/index.php'; ?>">
                    <i class="bi bi-dropbox"></i>
                    Life Saver
                </a>
                <h3 class="m-0 text-secondary">SECRETAIRE</h3>
            </div>

        </div>
    </nav>