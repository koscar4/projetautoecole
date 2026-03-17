<?php
// Tableau de bord : statistiques
$nbCandidats  = count($modele->selectAll_candidats());
$nbMoniteurs  = count($modele->selectAll_moniteurs());
$nbVoitures   = count($modele->selectAll_voitures());
$nbLecons     = count($modele->selectAll_lecons());
$nbFactures   = count($modele->selectAll_facturations());

$vue = 'Vue/Vue/vue_home.php';
