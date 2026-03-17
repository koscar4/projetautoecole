<?php
session_start();
if (isset($_SESSION['eleve_id'])) {
    header('Location: mon_espace.php');
    exit;
}
require_once '../modele/modele.autoecole.php';
$modele = new ModeleAutoEcole();

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eleve = $modele->getEleveByLogin($_POST['login'] ?? '');
    if ($eleve && password_verify($_POST['mot_de_passe'] ?? '', $eleve['mot_de_passe'])) {
        $_SESSION['eleve_id']  = $eleve['idcandidat'];
        $_SESSION['eleve_nom'] = $eleve['prenom'] . ' ' . $eleve['nom'];
        header('Location: mon_espace.php');
        exit;
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castellane Auto — Connexion élève</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --rouge: #C0392B; --rouge2: #96281B; --or: #D4A017;
            --noir: #1A1A1A; --gris: #F5F4F0; --blanc: #FFFFFF; --texte: #2D2D2D;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--blanc); color: var(--texte); }

        nav {
            position: sticky; top: 0; z-index: 100; background: var(--noir);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 3rem; height: 68px; border-bottom: 3px solid var(--rouge);
        }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--blanc); letter-spacing: 1px; }
        .logo span { color: var(--or); }
        .nav-links { display: flex; gap: 2rem; list-style: none; }
        .nav-links a { color: #ccc; text-decoration: none; font-size: .9rem; letter-spacing: .5px; text-transform: uppercase; transition: color .2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--or); }

        .page-wrap {
            min-height: calc(100vh - 68px);
            background: linear-gradient(135deg, var(--noir) 55%, var(--rouge2) 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 3rem 1rem;
        }
        .card {
            background: var(--blanc); border-radius: 6px;
            padding: 3rem 2.5rem; width: 100%; max-width: 440px;
            box-shadow: 0 24px 64px rgba(0,0,0,.45);
        }
        .badge { display: inline-block; background: #2980b9; color: #fff; font-size: .7rem; padding: .2rem .7rem; letter-spacing: 1px; text-transform: uppercase; border-radius: 2px; margin-bottom: 1.2rem; }
        h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: var(--noir); margin-bottom: .3rem; }
        .sous-titre { color: #888; font-size: .9rem; margin-bottom: 2rem; }
        label { display: block; font-size: .9rem; font-weight: 500; margin-bottom: .4rem; color: #444; }
        input[type=email], input[type=password] {
            width: 100%; padding: .65rem .9rem; border: 1.5px solid #ddd;
            border-radius: 4px; font-size: .95rem; font-family: inherit;
            transition: border-color .2s; margin-bottom: 1.2rem;
        }
        input:focus { outline: none; border-color: var(--rouge); }
        .btn-submit {
            width: 100%; background: var(--rouge); color: #fff; border: none;
            padding: .8rem; font-size: 1rem; font-weight: 500; border-radius: 4px;
            cursor: pointer; transition: background .2s; font-family: inherit;
        }
        .btn-submit:hover { background: var(--rouge2); }
        .erreur { background: #fdecea; color: #922; border-left: 3px solid var(--rouge); padding: .7rem 1rem; font-size: .88rem; margin-bottom: 1.2rem; border-radius: 2px; }
        .liens { text-align: center; margin-top: 1.5rem; font-size: .85rem; color: #888; }
        .liens a { color: var(--rouge); text-decoration: none; }
        .liens a:hover { text-decoration: underline; }
        footer { background: #111; color: #555; text-align: center; padding: 1.2rem; font-size: .8rem; border-top: 1px solid #222; }
        footer a { color: var(--rouge); text-decoration: none; }
    </style>
</head>
<body>

<nav>
    <div class="logo">Castellane <span>Auto</span></div>
    <ul class="nav-links">
        <li><a href="accueil.htm">Accueil</a></li>
        <li><a href="tarifs.htm">Tarifs</a></li>
        <li><a href="photos.htm">Photos</a></li>
        <li><a href="accueil.htm#contact">Contact</a></li>
        <li><a href="login.php" class="active" style="color:var(--or)">Mon espace</a></li>
    </ul>
</nav>

<div class="page-wrap">
    <div class="card">
        <span class="badge">Espace élève</span>
        <h1>Connexion</h1>
        <p class="sous-titre">Accédez à vos leçons et factures</p>

        <?php if ($erreur): ?>
            <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Adresse email</label>
            <input type="email" name="login" autofocus required
                value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">

            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>

        <div class="liens">
            Pas encore de compte ? <a href="inscription.php">S'inscrire gratuitement</a>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 Castellane Auto — Toulon &nbsp;|&nbsp;
    <a href="accueil.htm">Accueil</a> &nbsp;·&nbsp;
    <a href="tarifs.htm">Tarifs</a> &nbsp;·&nbsp;
    <a href="photos.htm">Photos</a>
</footer>

</body>
</html>
