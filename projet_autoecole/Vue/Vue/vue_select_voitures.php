<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>🚗 Gestion des Véhicules</h2>
    <a href="index.php?page=5&action=add" class="btn btn-primary">+ Ajouter un véhicule</a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Immatriculation</th><th>Marque / Modèle</th><th>Boîte</th><th>Année</th><th>Kilométrage</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($lesVoitures as $v): ?>
        <tr>
            <td><?= $v['idvoiture'] ?></td>
            <td><strong><?= htmlspecialchars($v['immatriculation']) ?></strong></td>
            <td><?= htmlspecialchars($v['marque'] . ' ' . $v['nom_modele']) ?></td>
            <td><span class="badge bg-secondary"><?= $v['type_boite'] ?></span></td>
            <td><?= $v['annee'] ?></td>
            <td><?= number_format($v['kilometrage_total'], 0, ',', ' ') ?> km</td>
            <td>
                <a href="index.php?page=5&action=edit&idvoiture=<?= $v['idvoiture'] ?>" class="btn btn-sm btn-warning">✏️</a>
                <a href="index.php?page=5&action=sup&idvoiture=<?= $v['idvoiture'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce véhicule ?')">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
