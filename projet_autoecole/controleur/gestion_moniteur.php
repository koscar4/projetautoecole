<?php
$leMoniteur   = null;
$lesMoniteurs = $modele->selectAll_moniteurs();

if ($action === 'add') {
    $vue = 'Vue/Vue/vue_insert_moniteur.php';

} elseif ($action === 'edit') {
    $leMoniteur = $modele->selectWhere_moniteur($_GET['idmoniteur']);
    $vue = 'Vue/Vue/vue_insert_moniteur.php';

} elseif ($action === 'sup') {
    $modele->delete_moniteur($_GET['idmoniteur']);
    $_SESSION['msg'] = 'Moniteur supprimé.';
    header('Location: index.php?page=3');
    exit;

} elseif (isset($_POST['Valider'])) {
    $modele->insert_moniteur($_POST);
    $_SESSION['msg'] = 'Moniteur ajouté avec succès.';
    header('Location: index.php?page=3');
    exit;

} elseif (isset($_POST['Modifier'])) {
    $modele->update_moniteur($_POST);
    $_SESSION['msg'] = 'Moniteur modifié avec succès.';
    header('Location: index.php?page=3');
    exit;

} else {
    $vue = 'Vue/Vue/vue_select_moniteurs.php';
}
