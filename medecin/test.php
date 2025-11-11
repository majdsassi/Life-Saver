<?php
session_start();
require_once '../config.php';
require_once '../utils/connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location:" . DOMAIN . "login.php?error=401");
    exit;
}

if ($_SESSION["user_role"] != "MEDECIN") {
    header("Location:" . DOMAIN . "login.php?error=403");
    exit;
}

if (!isset($_GET["id_don"]) || empty($_GET["id_don"])) {
    header("Location:" . DOMAIN . "medecin/dons.php");
    exit;
}

// Validate and sanitize the id_don parameter
$id_don = $_GET["id_don"] ; 
if ($id_don <= 0) {
    header("Location:" . DOMAIN . "medecin/dons.php");
    exit;
}

include 'includes/header.php';
include "includes/sidebar.php";

// Prepare and execute query with parameterized statement
try {
    $stmt = $pdo->prepare("SELECT * FROM dons WHERE id_don = ?");
    $stmt->execute([$id_don]);
    $don = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$don) {
        echo "<div class='alert alert-danger'>Don not found.</div>";
        include 'includes/footer.php';
        exit;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "<div class='alert alert-danger'>Error retrieving donation information.</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Validation Des Tests</h2>
    <div class="form-container">
        <h2 class="form-title">Validation Test</h2>
        <form method="POST" action="/handlers/testHandler.php">
            <input type="hidden" name="id_don" value="<?php echo htmlspecialchars($id_don); ?>">
            
            <div class="mb-3">
                <label for="confirm" class="form-label required-field">Is confirmed :</label>
                <select class="form-select" id="confirm" name="confirm" required>
                    <option value="">Please select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label required-field">Doctor Note</label>
                <textarea class="form-control" id="note" name="note" rows="5" placeholder="Enter your notes here..."></textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-danger">Submit Form</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>