<h2>📊 Planning & États Périodiques</h2>

<div class="row g-4">
    <!-- Planning moniteur -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">📅 Planning hebdomadaire par moniteur</h5>
            </div>
            <div class="card-body">
                <form method="get" class="row g-2 align-items-end mb-4">
                    <input type="hidden" name="page" value="7">
                    <div class="col-md-3">
                        <label class="form-label">Moniteur</label>
                        <select name="idmoniteur" class="form-select">
                            <option value="">— Tous —</option>
                            <?php foreach ($lesMoniteurs as $m): ?>
                                <option value="<?= $m['idmoniteur'] ?>"
                                    <?= ($_GET['idmoniteur'] ?? '') == $m['idmoniteur'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Du</label>
                        <input type="date" name="date_debut" class="form-control"
                            value="<?= $_GET['date_debut'] ?? $date_deb ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Au</label>
                        <input type="date" name="date_fin" class="form-control"
                            value="<?= $_GET['date_fin'] ?? $date_fin ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">🔍 Afficher</button>
                    </div>
                </form>

                <?php if (!empty($lesPlannings)): ?>
                <table class="table table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th><th>Début</th><th>Fin</th><th>Durée</th><th>Candidat</th><th>Véhicule</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lesPlannings as $p): ?>
                        <tr>
                            <td><?= date('D d/m/Y', strtotime($p['date_lecon'])) ?></td>
                            <td><?= substr($p['heure_debut'],0,5) ?></td>
                            <td><?= substr($p['heure_fin'],0,5) ?></td>
                            <td><span class="badge bg-info text-dark"><?= number_format($p['duree_heures'],1) ?>h</span></td>
                            <td><?= htmlspecialchars($p['candidat']) ?></td>
                            <td><?= htmlspecialchars($p['vehicule']) ?> <small class="text-muted"><?= $p['immatriculation'] ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong><?= number_format(array_sum(array_column($lesPlannings,'duree_heures')),1) ?>h</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
                <?php elseif (!empty($_GET['idmoniteur'])): ?>
                    <p class="text-muted">Aucune leçon sur cette période.</p>
                <?php else: ?>
                    <p class="text-muted">Sélectionnez un moniteur et une période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Suivi km mensuel -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">🛞 Suivi kilométrique mensuel par véhicule</h5>
            </div>
            <div class="card-body">
                <form method="get" class="row g-2 align-items-end mb-4">
                    <input type="hidden" name="page" value="7">
                    <?php if (!empty($_GET['idmoniteur'])): ?>
                        <input type="hidden" name="idmoniteur" value="<?= $_GET['idmoniteur'] ?>">
                    <?php endif; ?>
                    <div class="col-md-2">
                        <label class="form-label">Année</label>
                        <input type="number" name="annee" class="form-control" min="2020" max="2030"
                            value="<?= $annee ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mois</label>
                        <select name="mois" class="form-select">
                            <?php
                            $moisNoms = ['','Janvier','Février','Mars','Avril','Mai','Juin',
                                         'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                            for ($i=1; $i<=12; $i++):
                            ?>
                                <option value="<?= $i ?>" <?= $mois == $i ? 'selected':'' ?>><?= $moisNoms[$i] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">🔍 Afficher</button>
                    </div>
                </form>

                <?php if (!empty($lesKm)): ?>
                <table class="table table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Véhicule</th><th>Immatriculation</th><th>Km début</th><th>Km fin</th><th>Km parcourus</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lesKm as $k): ?>
                        <tr>
                            <td><?= htmlspecialchars($k['vehicule_nom']) ?></td>
                            <td><?= $k['immatriculation'] ?></td>
                            <td><?= number_format($k['km_debut'],0,',',' ') ?></td>
                            <td><?= number_format($k['km_fin'],0,',',' ') ?></td>
                            <td><strong><?= number_format($k['km_parcourus'],0,',',' ') ?> km</strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4"><strong>Total</strong></td>
                            <td><strong><?= number_format(array_sum(array_column($lesKm,'km_parcourus')),0,',',' ') ?> km</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <?php else: ?>
                    <p class="text-muted">Aucune donnée kilométrique pour cette période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
