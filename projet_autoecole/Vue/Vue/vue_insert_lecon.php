<h2><?= $laLecon ? '✏️ Modifier la leçon' : '➕ Planifier une leçon' ?></h2>

<form method="post" action="index.php?page=4" class="mt-3" style="max-width:700px;">    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Date *</label>
            <input type="date" name="date_lecon" class="form-control" required
                value="<?= $laLecon['date_lecon'] ?? date('Y-m-d') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Heure début *</label>
            <input type="time" name="heure_debut" class="form-control" required
                value="<?= $laLecon['heure_debut'] ?? '09:00' ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Heure fin *</label>
            <input type="time" name="heure_fin" class="form-control" required
                value="<?= $laLecon['heure_fin'] ?? '11:00' ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Candidat *</label>
            <select name="idcandidat" class="form-select" required>
                <option value="">— Choisir —</option>
                <?php foreach ($lesCandidats as $c): ?>
                    <option value="<?= $c['idcandidat'] ?>"
                        <?= ($laLecon['idcandidat'] ?? '') == $c['idcandidat'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Moniteur *</label>
            <select name="idmoniteur" class="form-select" required>
                <option value="">— Choisir —</option>
                <?php foreach ($lesMoniteurs as $m): ?>
                    <option value="<?= $m['idmoniteur'] ?>"
                        <?= ($laLecon['idmoniteur'] ?? '') == $m['idmoniteur'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?> (<?= $m['permis'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Véhicule *</label>
            <select name="idvoiture" class="form-select" required>
                <option value="">— Choisir —</option>
                <?php foreach ($lesVoitures as $v): ?>
                    <option value="<?= $v['idvoiture'] ?>"
                        <?= ($laLecon['idvoiture'] ?? '') == $v['idvoiture'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['marque'] . ' ' . $v['nom_modele']) ?> — <?= $v['immatriculation'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mt-4">
        <?php if ($laLecon): ?>
            <input type="hidden" name="idlecon" value="<?= $laLecon['idlecon'] ?>">
            <button type="submit" name="Modifier" value="Modifier" class="btn btn-warning">💾 Modifier</button>
        <?php else: ?>
            <button type="submit" name="Valider" value="Valider" class="btn btn-primary">💾 Enregistrer</button>
        <?php endif; ?>
        <a href="index.php?page=4" class="btn btn-secondary ms-2">Annuler</a>
    </div>
</form>
