<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>👤 Gestion des Candidats</h2>
    <a href="index.php?page=2&action=add" class="btn btn-primary">+ Ajouter un candidat</a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom / Prénom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Date prévu code</th>
            <th>Date prévu permis</th>
            <th>Établissement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($lesCandidats)): ?>
        <?php foreach ($lesCandidats as $c): ?>
        <tr>
            <td><?= $c['idcandidat'] ?></td>
            <td><strong><?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?></strong></td>
            <td><?= htmlspecialchars($c['tel'] ?? '—') ?></td>
            <td><?= htmlspecialchars($c['email'] ?? '—') ?></td>
            <td><?= $c['date_prevue_code'] ? date('d/m/Y', strtotime($c['date_prevue_code'])) : '<span class="text-muted">—</span>' ?></td>
            <td><?= $c['date_prevue_permis'] ? date('d/m/Y', strtotime($c['date_prevue_permis'])) : '<span class="text-muted">—</span>' ?></td>
            <td><?= htmlspecialchars($c['nom_etablissement'] ?? '—') ?></td>
            <td>
                <a href="index.php?page=2&action=edit&idcandidat=<?= $c['idcandidat'] ?>" class="btn btn-sm btn-warning">✏️</a>
                <a href="index.php?page=2&action=sup&idcandidat=<?= $c['idcandidat'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce candidat ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8" class="text-center text-muted">Aucun candidat enregistré.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
