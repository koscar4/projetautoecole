<?php
session_start();
if (!isset($_SESSION['eleve_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../modele/modele.autoecole.php';
$modele = new ModeleAutoEcole();

$eleve    = $modele->selectWhere_candidat($_SESSION['eleve_id']);
$lecons   = $modele->lecons_eleve($_SESSION['eleve_id']);
$factures = $modele->factures_eleve($_SESSION['eleve_id']);

$totalHeures = array_sum(array_column($lecons, 'duree_heures'));
$totalPaye   = array_sum(array_map(fn($f) => $f['statut']==='payee' ? $f['montant_total'] : 0, $factures));
$totalDu     = array_sum(array_map(fn($f) => $f['statut']==='en_attente' ? $f['montant_total'] : 0, $factures));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castellane Auto — Mon espace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --rouge:#C0392B; --rouge2:#96281B; --or:#D4A017; --noir:#1A1A1A; --gris:#F5F4F0; --blanc:#FFFFFF; --texte:#2D2D2D; }
        body { font-family: 'DM Sans', sans-serif; background: var(--gris); color: var(--texte); }

        /* TOPBAR */
        nav {
            background: var(--noir); display: flex; align-items: center;
            justify-content: space-between; padding: 0 2.5rem;
            height: 68px; border-bottom: 3px solid var(--rouge);
            position: sticky; top: 0; z-index: 100;
        }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--blanc); letter-spacing: 1px; }
        .logo span { color: var(--or); }
        .nav-right { display: flex; align-items: center; gap: 1.2rem; }
        .badge-eleve { background: #2980b9; color: #fff; font-size: .7rem; padding: .2rem .6rem; border-radius: 2px; letter-spacing: 1px; text-transform: uppercase; }
        .user-name { color: #ccc; font-size: .88rem; }
        .btn-logout {
            border: 1px solid rgba(255,255,255,.25); color: #bbb; background: none;
            padding: .3rem .9rem; border-radius: 3px; font-size: .82rem;
            cursor: pointer; text-decoration: none; transition: .2s;
        }
        .btn-logout:hover { border-color: var(--rouge); color: #fff; }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, var(--noir) 55%, var(--rouge2) 100%);
            padding: 3rem 2.5rem; color: var(--blanc);
        }
        .hero h1 { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: .4rem; }
        .hero p { color: #bbb; font-size: 1rem; }

        /* LAYOUT */
        main { max-width: 1050px; margin: 2.5rem auto; padding: 0 1.5rem 3rem; }

        /* CARDS STATS */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2.5rem; }
        .stat-card {
            background: var(--blanc); border-radius: 6px; padding: 1.5rem;
            text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,.06);
            border-top: 3px solid var(--rouge);
        }
        .stat-card .icon { font-size: 1.8rem; margin-bottom: .5rem; }
        .stat-card .val { font-size: 2rem; font-weight: 700; color: var(--rouge); line-height: 1; }
        .stat-card .lbl { font-size: .82rem; color: #888; margin-top: .3rem; }

        /* SECTION */
        .section { background: var(--blanc); border-radius: 6px; padding: 1.8rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .section-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--noir); margin-bottom: 1.2rem; padding-bottom: .6rem; border-bottom: 2px solid var(--gris); }

        /* INFOS */
        .infos-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.2rem; }
        .info-item .lbl { font-size: .75rem; color: #999; text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem; }
        .info-item .val { font-size: .95rem; font-weight: 500; }
        .date-pill {
            display: inline-block; padding: .25rem .7rem; border-radius: 20px;
            font-size: .82rem; font-weight: 600;
        }
        .date-pill.set { background: #d4edda; color: #155724; }
        .date-pill.none { background: #f0f0f0; color: #888; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        thead th { background: var(--noir); color: #fff; padding: .7rem 1rem; text-align: left; font-size: .78rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 500; }
        tbody tr { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
        tbody tr:hover { background: #fafafa; }
        td { padding: .75rem 1rem; }
        .badge { display: inline-block; padding: .2rem .6rem; border-radius: 20px; font-size: .78rem; font-weight: 600; }
        .badge-info { background: #cff4fc; color: #055160; }
        .badge-success { background: #d1e7dd; color: #0f5132; }
        .badge-warning { background: #fff3cd; color: #664d03; }
        .badge-danger { background: #f8d7da; color: #842029; }
        .empty { text-align: center; color: #aaa; padding: 2rem; font-size: .9rem; }

        footer { background: #111; color: #555; text-align: center; padding: 1.2rem; font-size: .8rem; border-top: 1px solid #222; }
        footer a { color: var(--rouge); text-decoration: none; }
    </style>
</head>
<body>

<nav>
    <div class="logo">Castellane <span>Auto</span></div>
    <div class="nav-right">
        <span class="badge-eleve">Espace élève</span>
        <span class="user-name">👤 <?= htmlspecialchars($_SESSION['eleve_nom']) ?></span>
        <a href="logout_eleve.php" class="btn-logout">Déconnexion</a>
    </div>
</nav>

<div class="hero">
    <h1>Bonjour, <?= htmlspecialchars($eleve['prenom']) ?> 👋</h1>
    <p>Bienvenue dans votre espace personnel Castellane Auto</p>
</div>

<main>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <div class="icon">📅</div>
            <div class="val"><?= count($lecons) ?></div>
            <div class="lbl">Leçons planifiées</div>
        </div>
        <div class="stat-card">
            <div class="icon">⏱️</div>
            <div class="val"><?= number_format($totalHeures, 1) ?>h</div>
            <div class="lbl">Heures de conduite</div>
        </div>
        <div class="stat-card">
            <div class="icon">✅</div>
            <div class="val"><?= number_format($totalPaye, 0, ',', ' ') ?> €</div>
            <div class="lbl">Montant payé</div>
        </div>
        <div class="stat-card">
            <div class="icon">⏳</div>
            <div class="val"><?= number_format($totalDu, 0, ',', ' ') ?> €</div>
            <div class="lbl">En attente de paiement</div>
        </div>
    </div>

    <!-- Infos personnelles -->
    <div class="section">
        <div class="section-title">📋 Mes informations</div>
        <div class="infos-grid">
            <div class="info-item">
                <div class="lbl">Nom complet</div>
                <div class="val"><?= htmlspecialchars($eleve['nom'] . ' ' . $eleve['prenom']) ?></div>
            </div>
            <div class="info-item">
                <div class="lbl">Email</div>
                <div class="val"><?= htmlspecialchars($eleve['email'] ?? '—') ?></div>
            </div>
            <div class="info-item">
                <div class="lbl">Téléphone</div>
                <div class="val"><?= htmlspecialchars($eleve['tel'] ?? '—') ?></div>
            </div>
            <div class="info-item">
                <div class="lbl">Date prévue — Code</div>
                <div class="val">
                    <?php if ($eleve['date_prevue_code']): ?>
                        <span class="date-pill set">📅 <?= date('d/m/Y', strtotime($eleve['date_prevue_code'])) ?></span>
                    <?php else: ?>
                        <span class="date-pill none">Non fixée</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-item">
                <div class="lbl">Date prévue — Permis</div>
                <div class="val">
                    <?php if ($eleve['date_prevue_permis']): ?>
                        <span class="date-pill set">🎯 <?= date('d/m/Y', strtotime($eleve['date_prevue_permis'])) ?></span>
                    <?php else: ?>
                        <span class="date-pill none">Non fixée</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Leçons -->
    <div class="section">
        <div class="section-title">📅 Mes leçons de conduite</div>
        <?php if (!empty($lecons)): ?>
        <table>
            <thead>
                <tr><th>Date</th><th>Horaire</th><th>Durée</th><th>Moniteur</th><th>Véhicule</th></tr>
            </thead>
            <tbody>
            <?php foreach ($lecons as $l): ?>
            <tr>
                <td><?= date('D d/m/Y', strtotime($l['date_lecon'])) ?></td>
                <td><?= substr($l['heure_debut'],0,5) ?> → <?= substr($l['heure_fin'],0,5) ?></td>
                <td><span class="badge badge-info"><?= number_format($l['duree_heures'],1) ?>h</span></td>
                <td><?= htmlspecialchars($l['moniteur_nom']) ?></td>
                <td><?= htmlspecialchars($l['vehicule_nom']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="empty">Aucune leçon planifiée pour le moment.<br>Contactez-nous pour réserver votre première leçon.</p>
        <?php endif; ?>
    </div>

    <!-- Factures -->
    <div class="section">
        <div class="section-title">💶 Mes factures</div>
        <?php if (!empty($factures)): ?>
        <table>
            <thead>
                <tr><th>Date</th><th>Mode de facturation</th><th>Montant</th><th>Statut</th></tr>
            </thead>
            <tbody>
            <?php foreach ($factures as $f):
                $bc = match($f['statut']) {
                    'payee'      => 'badge-success',
                    'en_attente' => 'badge-warning',
                    'annulee'    => 'badge-danger',
                    default      => ''
                };
                $label = match($f['statut']) {
                    'payee'      => '✅ Payée',
                    'en_attente' => '⏳ En attente',
                    'annulee'    => '❌ Annulée',
                    default      => $f['statut']
                };
            ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
                <td><?= ucfirst(str_replace('_',' ', $f['mode_facturation'])) ?></td>
                <td><strong><?= number_format($f['montant_total'],2,',',' ') ?> €</strong></td>
                <td><span class="badge <?= $bc ?>"><?= $label ?></span></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="empty">Aucune facture pour le moment.</p>
        <?php endif; ?>
    </div>

</main>

<footer>
    &copy; 2025 Castellane Auto — 27 bd du Général-de-Gaulle, 83100 Toulon &nbsp;|&nbsp;
    <a href="accueil.htm">Accueil</a> &nbsp;·&nbsp;
    <a href="tarifs.htm">Tarifs</a>
</footer>

</body>
</html>
