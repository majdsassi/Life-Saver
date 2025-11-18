<?php 
session_start();
require_once '../config.php';
require_once '../utils/connection.php'; 
if(isset($_SESSION["user_id"])){
    if($_SESSION["user_role"] == "MEDECIN") {
         include 'includes/header.php'; 
         include "includes/sidebar.php"; ?> 
          <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="bi bi-clipboard2-pulse me-2"></i>
                                Blood Test Results
                            </h3>
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-database me-1"></i>
                                Test Records
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            
                            $query = "SELECT `id_test`, `id_don`, `date_test`, `est_conforme`, `notes_medecin` FROM `tests_don` WHERE 1";
                            $stmt = $pdo->query($query);
                            
                            if ($stmt->rowCount() > 0) {
                        ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Test ID</th>
                                        <th scope="col">Donation ID</th>
                                        <th scope="col" class="date-column">Test Date</th>
                                        <th scope="col" class="status-column">Status</th>
                                        <th scope="col">Doctor's Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $status_class = $row['est_conforme'] ? 'bg-success' : 'bg-danger';
                                        $status_text = $row['est_conforme'] ? 'Conforme' : 'Non Conforme';
                                        $status_icon = $row['est_conforme'] ? 'bi-check-circle' : 'bi-x-circle';
                                        
                                        
                                        $test_date = $row['date_test'];
                                        
                                        // Handle empty notes
                                        $notes = !empty($row['notes_medecin']) ? $row['notes_medecin'] : '<span class="text-muted fst-italic">No notes</span>';
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $counter; ?></th>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($row['id_test']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?php echo htmlspecialchars($row['id_don']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                                            <?php echo $test_date; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $status_class; ?> conforme-badge">
                                                <i class="bi <?php echo $status_icon; ?> me-1"></i>
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td class="notes-cell">
                                            <?php echo $notes; ?>
                                        </td>
                                    </tr>
                                    <?php
                                        $counter++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="bi bi-graph-up me-2"></i>Test Summary
                                        </h6>
                                        <?php
                                        // Reset pointer and get statistics
                                        $stmt->execute();
                                        $total_tests = 0;
                                        $conforme_count = 0;
                                        
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $total_tests++;
                                            if ($row['est_conforme']) {
                                                $conforme_count++;
                                            }
                                        }
                                        
                                        $non_conforme_count = $total_tests - $conforme_count;
                                        $conforme_percentage = $total_tests > 0 ? round(($conforme_count / $total_tests) * 100, 2) : 0;
                                        ?>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h4 class="text-primary mb-0"><?php echo $total_tests; ?></h4>
                                                    <small class="text-muted">Total Tests</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h4 class="text-success mb-0"><?php echo $conforme_count; ?></h4>
                                                    <small class="text-muted">Conforme</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div>
                                                    <h4 class="text-danger mb-0"><?php echo $non_conforme_count; ?></h4>
                                                    <small class="text-muted">Non Conforme</small>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($total_tests > 0): ?>
                                        <div class="mt-3">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?php echo $conforme_percentage; ?>%" 
                                                     aria-valuenow="<?php echo $conforme_percentage; ?>" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo $conforme_percentage; ?>% of tests are conforme</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                            } else {
                                echo '
                                <div class="alert alert-warning text-center">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No test records found in the database.
                                </div>';
                            }
                        } catch (PDOException $e) {
                            echo '
                            <div class="alert alert-danger text-center">
                                <i class="bi bi-x-circle me-2"></i>
                                Error retrieving test data: ' . htmlspecialchars($e->getMessage()) . '
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    



 <?php include 'includes/footer.php';
    }
    else{
        header("Location:" . DOMAIN . "login.php?error=403");
        exit; 
    }
}
else{
        header("Location:" . DOMAIN . "login.php?error=401");
        exit; 
}