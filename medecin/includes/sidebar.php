<!-- sidebar.php -->
<div class="d-flex">
    <!-- Desktop Sidebar -->
    <nav id="sidebar" class="bg-dark text-white p-3 vh-100 position-fixed d-none d-lg-block" style="width:220px;">
        <h5 class="text-center mb-4">Menu</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="index.php" class="nav-link text-white">Tableau de Bord</a>
            </li>
            <li class="nav-item">
                <a href="dons.php" class="nav-link text-white">Dons</a>
            </li>
            <li class="nav-item">
                <a href="tests.php" class="nav-link text-white">Tests</a>
            </li>
        </ul>
    </nav>

    <!-- Mobile Sidebar (Bootstrap Offcanvas) -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="index.php" class="nav-link text-white">Tableau de Bord</a>
                </li>
                <li class="nav-item">
                    <a href="/medecin/dons.php" class="nav-link text-white">Dons</a>
                </li>
                <li class="nav-item">
                    <a href="/medecin/tests.php" class="nav-link text-white">Tests</a>
                    
                </li>
                <li class="nav-item">
                    <a href="/medecin/logout.php" class="btn btn-outline-light btn-sm mt-2 mt-lg-2">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                </li>
            </ul>
                
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="flex-grow-1 p-3" style="margin-left:220px;">
        <!-- Toggle button for mobile view -->
        <button class="btn btn-dark d-lg-none mb-3" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
            <i class="bi bi-list"></i> Menu
        </button>
