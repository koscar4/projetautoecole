<h2><?= $leMoniteur ? '✏️ Modifier le moniteur' : '➕ Ajouter un moniteur' ?></h2>

<form method="post" action="index.php?page=3" class="mt-3" style="max-width:600px;">    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nom *</label>
            <input type="text" name="nom" class="form-control" required
                value="<?= htmlspecialchars($leMoniteur['nom'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Prénom *</label>
            <input type="text" name="prenom" class="form-control" required
                value="<?= htmlspecialchars($leMoniteur['prenom'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control"
                value="<?= htmlspecialchars($leMoniteur['telephone'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="<?= htmlspecialchars($leMoniteur['email'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Type de permis *</label>
            <select name="permis" class="form-select" required>
                <?php foreach (['B','A','C','D','BE','CE'] as $p): ?>
                    <option value="<?= $p ?>" <?= ($leMoniteur['permis'] ?? '') === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mt-4">
        <?php if ($leMoniteur): ?>
            <input type="hidden" name="idmoniteur" value="<?= $leMoniteur['idmoniteur'] ?>">
            <button type="submit" name="Modifier" value="Modifier" class="btn btn-warning">💾 Modifier</button>
        <?php else: ?>
            <button type="submit" name="Valider" value="Valider" class="btn btn-primary">💾 Enregistrer</button>
        <?php endif; ?>
        <a href="index.php?page=3" class="btn btn-secondary ms-2">Annuler</a>
    </div>
</form>
