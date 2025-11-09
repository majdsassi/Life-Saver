<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title ?? "Admin" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Life Saver - Admin</a>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a href="http://localhost/Life-Saver/admin/utils/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>
</nav>
