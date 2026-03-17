<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>🎓 Gestion des Moniteurs</h2>
    <a href="index.php?page=3&action=add" class="btn btn-primary">+ Ajouter un moniteur</a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Nom</th><th>Prénom</th><th>Téléphone</th><th>Email</th><th>Permis</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($lesMoniteurs as $m): ?>
        <tr>
            <td><?= $m['idmoniteur'] ?></td>
            <td><?= htmlspecialchars($m['nom']) ?></td>
            <td><?= htmlspecialchars($m['prenom']) ?></td>
            <td><?= htmlspecialchars($m['telephone']) ?></td>
            <td><?= htmlspecialchars($m['email']) ?></td>
            <td><span class="badge bg-success"><?= htmlspecialchars($m['permis']) ?></span></td>
            <td>
                <a href="index.php?page=3&action=edit&idmoniteur=<?= $m['idmoniteur'] ?>" class="btn btn-sm btn-warning">✏️</a>
                <a href="index.php?page=3&action=sup&idmoniteur=<?= $m['idmoniteur'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce moniteur ?')">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
