<?php
session_start();

// Protection back-office
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

require_once 'modele/modele.autoecole.php';
$modele = new ModeleAutoEcole();

$page   = isset($_GET['page'])   ? (int)$_GET['page']   : 1;
$action = isset($_GET['action']) ? $_GET['action']       : '';

// Routing — AVANT tout HTML pour que header() fonctionne
switch ($page) {
    case 1:  require 'controleur/home.php';               break;
    case 2:  require 'controleur/gestion_candidat.php';   break;
    case 3:  require 'controleur/gestion_moniteur.php';   break;
    case 4:  require 'controleur/gestion_lecon.php';      break;
    case 5:  require 'controleur/gestion_voiture.php';    break;
    case 6:  require 'controleur/gestion_facturation.php'; break;
    case 7:  require 'controleur/gestion_planning.php';   break;
    default: require 'controleur/erreur.php';
}
// HTML seulement après les contrôleurs (qui peuvent avoir fait exit)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castellane Auto — Back-office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php?page=1">
            🚗 Castellane Auto
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $page==2?'active':'' ?>" href="index.php?page=2">👤 Candidats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==3?'active':'' ?>" href="index.php?page=3">🎓 Moniteurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==5?'active':'' ?>" href="index.php?page=5">🚗 Véhicules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==4?'active':'' ?>" href="index.php?page=4">📅 Leçons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==6?'active':'' ?>" href="index.php?page=6">💶 Facturation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==7?'active':'' ?>" href="index.php?page=7">📊 Planning</a>
                </li>
            </ul>
            <a href="front/accueil.htm" class="btn btn-outline-light btn-sm me-2" target="_blank">🌐 Site public</a>
            <span class="text-white-50 small me-2">👤 <?= htmlspecialchars($_SESSION['admin_nom'] ?? 'Admin') ?></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4 px-4">
    <?php
    // Afficher les messages flash
    if (isset($_SESSION['msg'])) {
        $type = $_SESSION['msg_type'] ?? 'success';
        echo "<div class='alert alert-{$type} alert-dismissible fade show'>{$_SESSION['msg']}
              <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        unset($_SESSION['msg'], $_SESSION['msg_type']);
    }

    // Charger la vue associée au contrôleur
    if (isset($vue)) {
        include $vue;
    }
    ?>
</div>

<footer class="mt-5 py-3 bg-dark text-center text-white-50 small">
    Castellane Auto — 27 bd du Général-de-Gaulle, 83100 Toulon &nbsp;|&nbsp; PPE2
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
