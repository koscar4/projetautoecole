<h2><?= $laVoiture ? '✏️ Modifier le véhicule' : '➕ Ajouter un véhicule' ?></h2>

<form method="post" action="index.php?page=5" class="mt-3" style="max-width:600px;">    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Immatriculation *</label>
            <input type="text" name="immatriculation" class="form-control" required
                placeholder="AB-123-CD"
                value="<?= htmlspecialchars($laVoiture['immatriculation'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Année</label>
            <input type="number" name="annee" class="form-control" min="2000" max="2030"
                value="<?= $laVoiture['annee'] ?? date('Y') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Kilométrage</label>
            <input type="number" name="kilometrage_total" class="form-control" min="0"
                value="<?= $laVoiture['kilometrage_total'] ?? 0 ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Modèle *</label>
            <select name="idmodele" class="form-select" required>
                <option value="">— Choisir —</option>
                <?php foreach ($lesModeles as $m): ?>
                    <option value="<?= $m['idmodele'] ?>"
                        <?= ($laVoiture['idmodele'] ?? '') == $m['idmodele'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['marque'] . ' ' . $m['modele'] . ' (' . $m['type_boite'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mt-4">
        <?php if ($laVoiture): ?>
            <input type="hidden" name="idvoiture" value="<?= $laVoiture['idvoiture'] ?>">
            <button type="submit" name="Modifier" value="Modifier" class="btn btn-warning">💾 Modifier</button>
        <?php else: ?>
            <button type="submit" name="Valider" value="Valider" class="btn btn-primary">💾 Enregistrer</button>
        <?php endif; ?>
        <a href="index.php?page=5" class="btn btn-secondary ms-2">Annuler</a>
    </div>
</form>
