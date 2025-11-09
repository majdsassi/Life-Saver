<?php
require_once "./config.php" ;
require_once "/utils/connection.php" ;?>
<body>
<?php include "/includes/header.php"; ?>
<div class="container">
    <h1 class="section-title">Centers</h1>
    <div class="row">
<?php 
try {
    $stmt = $pdo->prepare("SELECT * FROM `centres_collecte`"); 
    $stmt->execute();
    $centres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($centres) > 0) {
        foreach ($centres as $centre) {
            echo "<div class='col-lg-3 col-md-6 mb-4'>
                    <div class='card'>
                        <img class='card-img-top' src='./images/hospital.png' alt='" . htmlspecialchars($centre['nom_centre']) . "'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . htmlspecialchars($centre['nom_centre']) . "</h5>
                            <p class='card-text'>" . htmlspecialchars($centre['ville']) . "</p>
                        </div>
                    </div>
                </div>";
        }
    } else {
        echo "<div class='col-12'><p class='text-center'>No centers found.</p></div>";
    }
} catch (PDOException $e) {
    header("Location:" . DOMAIN . "index.php?error=database_error");
    error_log("Database error: " . $e->getMessage());
    exit;
}
?>
    </div>
</div>
<?php include "/includes/footer.php"; ?> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
