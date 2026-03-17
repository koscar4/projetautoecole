<?php
$laFacturation  = null;
$lesFacturations = $modele->selectAll_facturations();
$lesCandidats   = $modele->selectAll_candidats();

if ($action === 'add') {
    $vue = 'Vue/Vue/vue_insert_facturation.php';

} elseif ($action === 'edit') {
    $laFacturation = $modele->selectWhere_facturation($_GET['idfacturation']);
    $vue = 'Vue/Vue/vue_insert_facturation.php';

} elseif ($action === 'sup') {
    $modele->delete_facturation($_GET['idfacturation']);
    $_SESSION['msg'] = 'Facture supprimée.';
    header('Location: index.php?page=6');
    exit;

} elseif (isset($_POST['Valider'])) {
    $modele->insert_facturation($_POST);
    $_SESSION['msg'] = 'Facture créée avec succès.';
    header('Location: index.php?page=6');
    exit;

} elseif (isset($_POST['Modifier'])) {
    $modele->update_facturation($_POST);
    $_SESSION['msg'] = 'Facture modifiée avec succès.';
    header('Location: index.php?page=6');
    exit;

} else {
    $vue = 'Vue/Vue/vue_select_facturations.php';
}
