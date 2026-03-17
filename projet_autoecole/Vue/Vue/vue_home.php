<h2 class="mb-4">🏠 Tableau de bord</h2>

<div class="row g-4 mb-5">
    <div class="col-md-4 col-lg-2">
        <a href="index.php?page=2" class="text-decoration-none">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-1">👤</div>
                    <div class="display-6 fw-bold text-primary"><?= $nbCandidats ?></div>
                    <div class="text-muted small">Candidats</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 col-lg-2">
        <a href="index.php?page=3" class="text-decoration-none">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-1">🎓</div>
                    <div class="display-6 fw-bold text-success"><?= $nbMoniteurs ?></div>
                    <div class="text-muted small">Moniteurs</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 col-lg-2">
        <a href="index.php?page=5" class="text-decoration-none">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-1">🚗</div>
                    <div class="display-6 fw-bold text-warning"><?= $nbVoitures ?></div>
                    <div class="text-muted small">Véhicules</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 col-lg-2">
        <a href="index.php?page=4" class="text-decoration-none">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-1">📅</div>
                    <div class="display-6 fw-bold text-info"><?= $nbLecons ?></div>
                    <div class="text-muted small">Leçons</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 col-lg-2">
        <a href="index.php?page=6" class="text-decoration-none">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-1">💶</div>
                    <div class="display-6 fw-bold text-danger"><?= $nbFactures ?></div>
                    <div class="text-muted small">Factures</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 col-lg-2">
        <a href="index.php?page=7" class="text-decoration-none">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-1">📊</div>
                    <div class="display-6 fw-bold text-secondary">—</div>
                    <div class="text-muted small">Planning</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="alert alert-info">
    <strong>Castellane Auto</strong> — 27 bd du Général-de-Gaulle, 83100 Toulon Cedex<br>
    Système de gestion informatisé — PPE2
</div>
