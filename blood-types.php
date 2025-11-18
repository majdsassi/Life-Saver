<?php
include "./includes/head.php"; ?>
<body class="app-theme public-theme">
<?php
include "./includes/header.php"; ?>
<section id="blood-types" class="blood-types">
        <div class="container">
            <h2 class="section-title">Blood Types Compatibility</h2>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">A+</div>
                        <p>Can donate to: A+, AB+</p>
                        <p>Can receive from: A+, A-, O+, O-</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">B+</div>
                        <p>Can donate to: B+, AB+</p>
                        <p>Can receive from: B+, B-, O+, O-</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">AB+</div>
                        <p>Can donate to: AB+</p>
                        <p>Can receive from: All blood types</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">O+</div>
                        <p>Can donate to: O+, A+, B+, AB+</p>
                        <p>Can receive from: O+, O-</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">A-</div>
                        <p>Can donate to: A+, A-, AB+, AB-</p>
                        <p>Can receive from: A-, O-</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">B-</div>
                        <p>Can donate to: B+, B-, AB+, AB-</p>
                        <p>Can receive from: B-, O-</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">AB-</div>
                        <p>Can donate to: AB+, AB-</p>
                        <p>Can receive from: AB-, A-, B-, O-</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="blood-type-card">
                        <div class="blood-type">O-</div>
                        <p>Can donate to: All blood types</p>
                        <p>Can receive from: O-</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include "./includes/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>