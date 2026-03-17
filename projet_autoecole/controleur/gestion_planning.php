<?php
$lesMoniteurs = $modele->selectAll_moniteurs();
$lesPlannings = [];
$lesKm        = [];

$annee = $_GET['annee'] ?? date('Y');
$mois  = $_GET['mois']  ?? date('m');

// Planning moniteur sélectionné
$idmon     = $_GET['idmoniteur'] ?? null;
$date_deb  = $_GET['date_debut'] ?? date('Y-m-d', strtotime('monday this week'));
$date_fin  = $_GET['date_fin']   ?? date('Y-m-d', strtotime('sunday this week'));

if ($idmon) {
    $lesPlannings = $modele->planning_moniteur($idmon, $date_deb, $date_fin);
}

// Suivi km mensuel
$lesKm = $modele->suivi_km_mensuel($annee, $mois);

$vue = 'Vue/Vue/vue_planning.php';
