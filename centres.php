<?php
    require_once "config.php";

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection error: " . $e->getMessage());
    }
?>
    <?php include "./includes/head.php";
    ?>
    <body class="app-theme public-theme">
        <?php include "./includes/header.php"; ?>

    <div class="container py-5">
        <h1 class="section-title">Collection Centers</h1>

        <div class="row">
            <?php
                try {
                    $stmt = $pdo->prepare("SELECT * FROM `centres_collecte`");
                    $stmt->execute();
                    $centres = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($centres) > 0) {
                        foreach ($centres as $centre) {
                            echo "
                        <div class='col-lg-3 col-md-6 mb-4'>
                            <div class='card'>
                                <div class='card-img-top'>
                                    <i class='fas fa-hospital'></i>
                                </div>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . htmlspecialchars($centre['nom_centre']) . "</h5>
                                    <p class='card-text'>
                                        <i class='fas fa-map-marker-alt location-icon'></i>
                                        " . htmlspecialchars($centre['ville']) . "
                                    </p>
                                </div>
                            </div>
                        </div>";
                        }
                    } else {
                        echo "
                    <div class='col-12'>
                        <div class='no-centers'>
                            <i class='fas fa-search'></i>
                            <h3>No Centers Found</h3>
                            <p>There are currently no collection centers available.</p>
                        </div>
                    </div>";
                    }
                } catch (PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    echo "
                <div class='col-12'>
                    <div class='alert alert-danger text-center'>
                        <i class='fas fa-exclamation-triangle'></i>
                        <h4>Unable to Load Centers</h4>
                        <p>Please try again later.</p>
                    </div>
                </div>";
                }
            ?>
        </div>
    </div>

    <?php include "./includes/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>