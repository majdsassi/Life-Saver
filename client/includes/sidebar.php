<?php
if (! defined('DOMAIN')) {
    require_once __DIR__ . '/../../config.php';
}

$currentPath = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));

$menuItems = [
    ['file' => 'index.php', 'label' => 'Tableau de Bord', 'icon' => 'bi-speedometer2'],
    ['file' => 'logout.php', 'label' =>'Déconnexion', 'icon' => 'bi-box-arrow-right'],
];
?>

<div class="app-shell d-flex">
    <nav class="app-sidebar d-none d-lg-flex flex-column">
        <div class="brand">Navigation</div>
        <ul class="nav flex-column gap-1">
            <?php foreach ($menuItems as $item): ?>
                <li class="nav-item">
                    <a href="<?php echo DOMAIN . 'client/' . $item['file']; ?>" class="nav-link<?php echo $currentPath === $item['file'] ? 'active' : ''; ?>">
                        <i class="bi                                                                         <?php echo $item['icon']; ?>"></i>
                        <?php echo $item['label']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminSidebarLabel">Navigation</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column gap-1">
                <?php foreach ($menuItems as $item): ?>
                    <li class="nav-item">
                        <a href="<?php echo DOMAIN . 'client/' . $item['file']; ?>" class="nav-link d-flex align-items-center gap-2<?php echo $currentPath === $item['file'] ? 'active' : ''; ?>">
                            <i class="bi                                                                                 <?php echo $item['icon']; ?>"></i>
                            <?php echo $item['label']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="app-content flex-grow-1">
        <button class="btn btn-dark d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas">
            <i class="bi bi-list me-1"></i> Menu
        </button>