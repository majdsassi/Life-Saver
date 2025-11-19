<?php 
include "./includes/head.php" ;
$errors = [ 
    "invalid_credentials" => "Username Or Password is incorrect",
    "missing_fields" => "Don't Send Empty fields!!",
    "database_error" => "Error 500",
    "403" => "403 Forbidden" ,
    "401" => "401 Unauthorized",
    "access_report" => "You Try To access The Handler Directly That's Forbidden a Report will be sent , Your IP : ".$_SERVER['REMOTE_ADDR']
];
?> 
<body class="app-theme public-theme">
<?php
  include "./includes/header.php" ; ?>
  <section class="auth-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10">
        <div class="card auth-card">
          <div class="row g-0">
            <div class="col-md-5 illustration d-none d-md-flex align-items-center justify-content-center p-4">
              <div class="text-center">
                <img src="./images/login.jpeg" alt="login" class="img-fluid rounded-4 shadow-lg mb-4">
                <h4 class="fw-bold">Rejoignez la mission</h4>
                <p class="mb-0">Chaque connexion nous rapproche d’une vie sauvée.</p>
              </div>
            </div>
            <div class="col-md-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5">

                <form action="handlers/clinetLoginHandler.php" method="POST" class="app-theme">

                  <div class="d-flex align-items-center mb-3">
                    <span class="navbar-brand text-dark fs-4">
                        <i class="bi bi-droplet-half text-danger me-2"></i>LifeSaver
                    </span>
                  </div>

                  <h5 class="fw-bold mb-4">Connectez-vous à votre espace</h5>
                  <?php 
                  if(isset($_GET["error"]) && isset($errors[$_GET["error"]])) {
                    echo '
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Erreur :</strong> ' . htmlspecialchars($errors[$_GET["error"]]) . '
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';

                  }
                  ?>

                  <div class="mb-3">
                    <label class="form-label" for="username">Nom d’utilisateur</label>
                    <input type="text" id="username" class="form-control form-control-lg"  name="username" required/>
                  </div>

                  <div class="mb-4">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" class="form-control form-control-lg" name="password" required />
                  </div>
                
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">Se connecter</button>
                  </div>
                  <div class="mb-4">
                    <a href="/login.php" class='link-primary'> Staff Login </a>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <?php include "./includes/footer.php" ; ?> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>