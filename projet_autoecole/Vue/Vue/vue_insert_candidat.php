<h2><?= $leCandidat ? '✏️ Modifier le candidat' : '➕ Ajouter un candidat' ?></h2>

<form method="post" action="index.php?page=2" class="mt-3" style="max-width:700px;">    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nom *</label>
            <input type="text" name="nom" class="form-control" required
                value="<?= htmlspecialchars($leCandidat['nom'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Prénom *</label>
            <input type="text" name="prenom" class="form-control" required
                value="<?= htmlspecialchars($leCandidat['prenom'] ?? '') ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Adresse</label>
            <input type="text" name="adresse" class="form-control"
                value="<?= htmlspecialchars($leCandidat['adresse'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Téléphone</label>
            <input type="text" name="tel" class="form-control"
                value="<?= htmlspecialchars($leCandidat['tel'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="<?= htmlspecialchars($leCandidat['email'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Date de naissance</label>
            <input type="date" name="date_naissance" class="form-control"
                value="<?= $leCandidat['date_naissance'] ?? '' ?>">
        </div>

        <!-- RG1 : Dates prévues code et permis -->
        <div class="col-12"><hr><p class="fw-bold text-primary mb-1">📋 Suivi administratif (RG1)</p></div>
        <div class="col-md-4">
            <label class="form-label">Date prévue du <strong>code</strong></label>
            <input type="date" name="date_prevue_code" class="form-control"
                value="<?= $leCandidat['date_prevue_code'] ?? '' ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Date prévue du <strong>permis</strong></label>
            <input type="date" name="date_prevue_permis" class="form-control"
                value="<?= $leCandidat['date_prevue_permis'] ?? '' ?>">
        </div>

        <!-- RG2 : Établissement étudiant -->
        <div class="col-12"><hr><p class="fw-bold text-primary mb-1">🏫 Étudiant (RG2) — facultatif</p></div>
        <div class="col-md-8">
            <label class="form-label">Établissement scolaire</label>
            <select name="idetablissement" class="form-select">
                <option value="">— Pas étudiant —</option>
                <?php foreach ($lesEtablissements as $e): ?>
                    <option value="<?= $e['idetablissement'] ?>"
                        <?= ($leCandidat['idetablissement'] ?? '') == $e['idetablissement'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['nom_etablissement']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <?php if ($leCandidat): ?>
            <input type="hidden" name="idcandidat" value="<?= $leCandidat['idcandidat'] ?>">
            <button type="submit" name="Modifier" value="Modifier" class="btn btn-warning">💾 Modifier</button>
        <?php else: ?>
            <button type="submit" name="Valider" value="Valider" class="btn btn-primary">💾 Enregistrer</button>
        <?php endif; ?>
        <a href="index.php?page=2" class="btn btn-secondary ms-2">Annuler</a>
    </div>
</form>
