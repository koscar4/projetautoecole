<?php
$laVoiture   = null;
$lesVoitures = $modele->selectAll_voitures();
$lesModeles  = $modele->selectAll_modeles();

if ($action === 'add') {
    $vue = 'Vue/Vue/vue_insert_voiture.php';

} elseif ($action === 'edit') {
    $laVoiture = $modele->selectWhere_voiture($_GET['idvoiture']);
    $vue = 'Vue/Vue/vue_insert_voiture.php';

} elseif ($action === 'sup') {
    $modele->delete_voiture($_GET['idvoiture']);
    $_SESSION['msg'] = 'Véhicule supprimé.';
    header('Location: index.php?page=5');
    exit;

} elseif (isset($_POST['Valider'])) {
    $modele->insert_voiture($_POST);
    $_SESSION['msg'] = 'Véhicule ajouté avec succès.';
    header('Location: index.php?page=5');
    exit;

} elseif (isset($_POST['Modifier'])) {
    $modele->update_voiture($_POST);
    $_SESSION['msg'] = 'Véhicule modifié avec succès.';
    header('Location: index.php?page=5');
    exit;

} else {
    $vue = 'Vue/Vue/vue_select_voitures.php';
}
