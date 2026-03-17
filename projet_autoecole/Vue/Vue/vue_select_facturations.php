<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>💶 Gestion de la Facturation</h2>
    <a href="index.php?page=6&action=add" class="btn btn-primary">+ Créer une facture</a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Date</th><th>Candidat</th><th>Mode</th><th>Détail</th><th>Total</th><th>Statut</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($lesFacturations)): ?>
        <?php foreach ($lesFacturations as $f): ?>
        <?php
            $badgeMode = match($f['mode_facturation']) {
                'heure'          => 'bg-primary',
                'forfait_pack'   => 'bg-success',
                'forfait_global' => 'bg-warning text-dark',
                default          => 'bg-secondary'
            };
            $badgeStatut = match($f['statut']) {
                'payee'      => 'bg-success',
                'en_attente' => 'bg-warning text-dark',
                'annulee'    => 'bg-danger',
                default      => 'bg-secondary'
            };
        ?>
        <tr>
            <td><?= $f['idfacturation'] ?></td>
            <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
            <td><?= htmlspecialchars($f['candidat_nom']) ?></td>
            <td><span class="badge <?= $badgeMode ?>"><?= str_replace('_',' ', $f['mode_facturation']) ?></span></td>
            <td class="small text-muted">
                <?php if ($f['mode_facturation'] === 'heure'): ?>
                    <?= $f['nb_heures'] ?>h × <?= $f['tarif_horaire'] ?>€
                <?php else: ?>
                    Forfait <?= $f['nb_heures_incluses'] ? $f['nb_heures_incluses'].'h incluses' : '' ?>
                <?php endif; ?>
            </td>
            <td><strong><?= number_format($f['montant_total'], 2, ',', ' ') ?> €</strong></td>
            <td><span class="badge <?= $badgeStatut ?>"><?= str_replace('_',' ', $f['statut']) ?></span></td>
            <td>
                <a href="index.php?page=6&action=edit&idfacturation=<?= $f['idfacturation'] ?>" class="btn btn-sm btn-warning">✏️</a>
                <a href="index.php?page=6&action=sup&idfacturation=<?= $f['idfacturation'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer cette facture ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8" class="text-center text-muted">Aucune facture enregistrée.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
