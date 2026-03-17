<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>📅 Gestion des Leçons</h2>
    <a href="index.php?page=4&action=add" class="btn btn-primary">+ Planifier une leçon</a>
</div>

<div class="alert alert-info small">
    ℹ️ Le système vérifie automatiquement les chevauchements (même candidat, moniteur ou véhicule).
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Date</th><th>Début</th><th>Fin</th><th>Durée</th>
            <th>Candidat</th><th>Moniteur</th><th>Véhicule</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($lesLecons)): ?>
        <?php foreach ($lesLecons as $l): ?>
        <tr>
            <td><?= $l['idlecon'] ?></td>
            <td><?= date('d/m/Y', strtotime($l['date_lecon'])) ?></td>
            <td><?= substr($l['heure_debut'],0,5) ?></td>
            <td><?= substr($l['heure_fin'],0,5) ?></td>
            <td><span class="badge bg-info text-dark"><?= number_format($l['duree_heures'],1) ?>h</span></td>
            <td><?= htmlspecialchars($l['candidat_nom']) ?></td>
            <td><?= htmlspecialchars($l['moniteur_nom']) ?></td>
            <td><?= htmlspecialchars($l['vehicule_nom']) ?><br>
                <small class="text-muted"><?= $l['immatriculation'] ?></small></td>
            <td>
                <a href="index.php?page=4&action=edit&idlecon=<?= $l['idlecon'] ?>" class="btn btn-sm btn-warning">✏️</a>
                <a href="index.php?page=4&action=sup&idlecon=<?= $l['idlecon'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer cette leçon ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="9" class="text-center text-muted">Aucune leçon planifiée.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
