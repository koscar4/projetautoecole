<h2><?= $laFacturation ? '✏️ Modifier la facture' : '➕ Créer une facture' ?></h2>

<form method="post" action="index.php?page=6" class="mt-3" style="max-width:700px;" id="formFacture">    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Candidat *</label>
            <select name="idcandidat" class="form-select" required>
                <option value="">— Choisir —</option>
                <?php foreach ($lesCandidats as $c): ?>
                    <option value="<?= $c['idcandidat'] ?>"
                        <?= ($laFacturation['idcandidat'] ?? '') == $c['idcandidat'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date facture *</label>
            <input type="date" name="date_facture" class="form-control" required
                value="<?= $laFacturation['date_facture'] ?? date('Y-m-d') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Mode de facturation *</label>
            <select name="mode_facturation" class="form-select" id="modeSelect" required
                onchange="afficherMode(this.value)">
                <option value="">— Choisir —</option>
                <option value="heure" <?= ($laFacturation['mode_facturation']??'') === 'heure' ? 'selected':'' ?>>À l'heure</option>
                <option value="forfait_pack" <?= ($laFacturation['mode_facturation']??'') === 'forfait_pack' ? 'selected':'' ?>>Forfait pack</option>
                <option value="forfait_global" <?= ($laFacturation['mode_facturation']??'') === 'forfait_global' ? 'selected':'' ?>>Forfait global</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select">
                <option value="en_attente" <?= ($laFacturation['statut']??'') === 'en_attente' ? 'selected':'' ?>>En attente</option>
                <option value="payee" <?= ($laFacturation['statut']??'') === 'payee' ? 'selected':'' ?>>Payée</option>
                <option value="annulee" <?= ($laFacturation['statut']??'') === 'annulee' ? 'selected':'' ?>>Annulée</option>
            </select>
        </div>
    </div>

    <!-- Mode heure -->
    <div id="bloc-heure" class="border rounded p-3 mt-3 bg-light" style="display:none;">
        <h6 class="text-primary">📋 Facturation à l'heure</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nombre d'heures</label>
                <input type="number" name="nb_heures" class="form-control" step="0.5" min="0"
                    value="<?= $laFacturation['nb_heures'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tarif horaire (€)</label>
                <input type="number" name="tarif_horaire" class="form-control" step="0.01" min="0"
                    value="<?= $laFacturation['tarif_horaire'] ?? '35.00' ?>">
            </div>
        </div>
    </div>

    <!-- Mode forfait -->
    <div id="bloc-forfait" class="border rounded p-3 mt-3 bg-light" style="display:none;">
        <h6 class="text-success">📦 Facturation forfait</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Montant forfait (€)</label>
                <input type="number" name="montant_forfait" class="form-control" step="0.01" min="0"
                    value="<?= $laFacturation['montant_forfait'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Heures incluses</label>
                <input type="number" name="nb_heures_incluses" class="form-control" min="0"
                    value="<?= $laFacturation['nb_heures_incluses'] ?? '' ?>">
            </div>
        </div>
    </div>

    <div class="mt-4">
        <?php if ($laFacturation): ?>
            <input type="hidden" name="idfacturation" value="<?= $laFacturation['idfacturation'] ?>">
            <button type="submit" name="Modifier" value="Modifier" class="btn btn-warning">💾 Modifier</button>
        <?php else: ?>
            <button type="submit" name="Valider" value="Valider" class="btn btn-primary">💾 Enregistrer</button>
        <?php endif; ?>
        <a href="index.php?page=6" class="btn btn-secondary ms-2">Annuler</a>
    </div>
</form>

<script>
function afficherMode(mode) {
    document.getElementById('bloc-heure').style.display   = mode === 'heure' ? 'block' : 'none';
    document.getElementById('bloc-forfait').style.display = (mode === 'forfait_pack' || mode === 'forfait_global') ? 'block' : 'none';
}
// Initialisation si modification
afficherMode(document.getElementById('modeSelect').value);
</script>
