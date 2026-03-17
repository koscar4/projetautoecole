<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: index.php?page=1');
    exit;
}
require_once 'modele/modele.autoecole.php';
$modele = new ModeleAutoEcole();

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin = $modele->getAdmin($_POST['login'] ?? '');
    if ($admin && password_verify($_POST['mot_de_passe'] ?? '', $admin['mot_de_passe'])) {
        $_SESSION['admin']     = $admin['login'];
        $_SESSION['admin_nom'] = $admin['prenom'] . ' ' . $admin['nom'];
        header('Location: index.php?page=1');
        exit;
    } else {
        $erreur = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castellane Auto — Administration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #1A1A1A;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .card {
            background: #fff; border-radius: 6px;
            padding: 3rem 2.5rem; width: 100%; max-width: 400px;
            box-shadow: 0 24px 64px rgba(0,0,0,.6);
        }
        .badge { display: inline-block; background: #C0392B; color: #fff; font-size: .7rem; padding: .2rem .7rem; letter-spacing: 1px; text-transform: uppercase; border-radius: 2px; margin-bottom: 1.2rem; }
        h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1A1A1A; margin-bottom: .3rem; }
        .sous-titre { color: #888; font-size: .88rem; margin-bottom: 2rem; }
        label { display: block; font-size: .88rem; font-weight: 500; margin-bottom: .4rem; color: #444; }
        input[type=text], input[type=password] {
            width: 100%; padding: .65rem .9rem; border: 1.5px solid #ddd;
            border-radius: 4px; font-size: .95rem; font-family: inherit;
            transition: border-color .2s; margin-bottom: 1.2rem;
        }
        input:focus { outline: none; border-color: #C0392B; }
        .btn {
            width: 100%; background: #C0392B; color: #fff; border: none;
            padding: .8rem; font-size: 1rem; font-weight: 500; border-radius: 4px;
            cursor: pointer; font-family: inherit; transition: background .2s;
        }
        .btn:hover { background: #96281B; }
        .erreur { background: #fdecea; color: #922; border-left: 3px solid #C0392B; padding: .7rem 1rem; font-size: .88rem; margin-bottom: 1.2rem; border-radius: 2px; }
        .lien-eleve { text-align: center; margin-top: 1.5rem; font-size: .82rem; color: #aaa; }
        .lien-eleve a { color: #C0392B; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <span class="badge">Back-office</span>
    <h1>🚗 Castellane Auto</h1>
    <p class="sous-titre">Connexion administrateur</p>

    <?php if ($erreur): ?>
        <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Identifiant</label>
        <input type="text" name="login" autofocus value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe">
        <button type="submit" class="btn">Se connecter</button>
    </form>

    <div class="lien-eleve">
        Vous êtes élève ? <a href="front/login.php">Accéder à votre espace</a>
    </div>
</div>
</body>
</html>
