<?php
session_start();
if (isset($_SESSION['eleve_id'])) {
    header('Location: mon_espace.php');
    exit;
}
require_once '../modele/modele.autoecole.php';
$modele = new ModeleAutoEcole();

$erreur = '';
$succes = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom   = trim($_POST['nom'] ?? '');
    $prenom= trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['mot_de_passe'] ?? '';
    $mdp2  = $_POST['mot_de_passe2'] ?? '';

    if (!$nom || !$prenom || !$email || !$mdp) {
        $erreur = 'Tous les champs obligatoires (*) doivent être remplis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } elseif (strlen($mdp) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($mdp !== $mdp2) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';
    } elseif ($modele->loginEleve_existe($email)) {
        $erreur = 'Un compte existe déjà avec cette adresse email.';
    } else {
        $modele->inscrireEleve([
            'nom'           => $nom,
            'prenom'        => $prenom,
            'email'         => $email,
            'tel'           => trim($_POST['tel'] ?? ''),
            'adresse'       => trim($_POST['adresse'] ?? ''),
            'date_naissance'=> $_POST['date_naissance'] ?: null,
            'mot_de_passe'  => password_hash($mdp, PASSWORD_DEFAULT),
        ]);
        $succes = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castellane Auto — Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --rouge:#C0392B; --rouge2:#96281B; --or:#D4A017; --noir:#1A1A1A; --gris:#F5F4F0; --blanc:#FFFFFF; --texte:#2D2D2D; }
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
        .nav-links a:hover { color: var(--or); }

        .page-wrap {
            min-height: calc(100vh - 68px);
            background: linear-gradient(135deg, var(--noir) 55%, var(--rouge2) 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 3rem 1rem;
        }
        .card {
            background: var(--blanc); border-radius: 6px;
            padding: 3rem 2.5rem; width: 100%; max-width: 560px;
            box-shadow: 0 24px 64px rgba(0,0,0,.45);
        }
        .badge { display: inline-block; background: #2980b9; color: #fff; font-size: .7rem; padding: .2rem .7rem; letter-spacing: 1px; text-transform: uppercase; border-radius: 2px; margin-bottom: 1.2rem; }
        h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: var(--noir); margin-bottom: .3rem; }
        .sous-titre { color: #888; font-size: .9rem; margin-bottom: 2rem; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .col-full { grid-column: 1 / -1; }
        label { display: block; font-size: .88rem; font-weight: 500; margin-bottom: .35rem; color: #444; }
        input[type=text], input[type=email], input[type=password], input[type=date], input[type=tel] {
            width: 100%; padding: .65rem .9rem; border: 1.5px solid #ddd;
            border-radius: 4px; font-size: .95rem; font-family: inherit;
            transition: border-color .2s; margin-bottom: 0;
        }
        input:focus { outline: none; border-color: var(--rouge); }
        .field { margin-bottom: 1rem; }
        .btn-submit {
            width: 100%; background: var(--rouge); color: #fff; border: none;
            padding: .8rem; font-size: 1rem; font-weight: 500; border-radius: 4px;
            cursor: pointer; transition: background .2s; font-family: inherit; margin-top: .5rem;
        }
        .btn-submit:hover { background: var(--rouge2); }
        .erreur { background: #fdecea; color: #922; border-left: 3px solid var(--rouge); padding: .7rem 1rem; font-size: .88rem; margin-bottom: 1.2rem; border-radius: 2px; }
        .succes { background: #eaf7ed; color: #1a6b30; border-left: 3px solid #27ae60; padding: 1rem 1.2rem; font-size: .95rem; border-radius: 2px; }
        .succes a { color: var(--rouge); font-weight: 600; }
        .liens { text-align: center; margin-top: 1.5rem; font-size: .85rem; color: #888; }
        .liens a { color: var(--rouge); text-decoration: none; }
        footer { background: #111; color: #555; text-align: center; padding: 1.2rem; font-size: .8rem; border-top: 1px solid #222; }
        footer a { color: var(--rouge); text-decoration: none; }
        .sep { border: none; border-top: 1px solid #eee; margin: 1.2rem 0; }
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
        <li><a href="login.php" style="color:var(--or)">Mon espace</a></li>
    </ul>
</nav>

<div class="page-wrap">
    <div class="card">
        <span class="badge">Inscription</span>
        <h1>Créer mon compte</h1>
        <p class="sous-titre">Accédez à votre suivi de formation en ligne</p>

        <?php if ($succes): ?>
            <div class="succes">
                ✅ Votre compte a été créé avec succès !<br>
                <a href="login.php">→ Se connecter maintenant</a>
            </div>
        <?php else: ?>

        <?php if ($erreur): ?>
            <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="row">
                <div class="field">
                    <label>Nom *</label>
                    <input type="text" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                </div>
                <div class="field">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                </div>
                <div class="field col-full">
                    <label>Adresse email * <small style="color:#aaa;font-weight:400;">(servira d'identifiant)</small></label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="field">
                    <label>Téléphone</label>
                    <input type="tel" name="tel" value="<?= htmlspecialchars($_POST['tel'] ?? '') ?>">
                </div>
                <div class="field">
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" value="<?= $_POST['date_naissance'] ?? '' ?>">
                </div>
                <div class="field col-full">
                    <label>Adresse postale</label>
                    <input type="text" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>">
                </div>
            </div>

            <hr class="sep">

            <div class="row">
                <div class="field">
                    <label>Mot de passe * <small style="color:#aaa;font-weight:400;">(6 car. min.)</small></label>
                    <input type="password" name="mot_de_passe" required>
                </div>
                <div class="field">
                    <label>Confirmer le mot de passe *</label>
                    <input type="password" name="mot_de_passe2" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>

        <div class="liens">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </div>

        <?php endif; ?>
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
