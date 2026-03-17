<?php
$leCandidat    = null;
$lesCandidats  = $modele->selectAll_candidats();
$lesEtablissements = $modele->selectAll_etablissements();

if ($action === 'add') {
    $vue = 'Vue/Vue/vue_insert_candidat.php';

} elseif ($action === 'edit') {
    $leCandidat = $modele->selectWhere_candidat($_GET['idcandidat']);
    $vue = 'Vue/Vue/vue_insert_candidat.php';

} elseif ($action === 'sup') {
    $modele->delete_candidat($_GET['idcandidat']);
    $_SESSION['msg'] = 'Candidat supprimé.';
    header('Location: index.php?page=2');
    exit;

} elseif (isset($_POST['Valider']) || isset($_POST['Modifier'])) {
    if (isset($_POST['Modifier'])) {
        $modele->update_candidat($_POST);
        $_SESSION['msg'] = 'Candidat modifié avec succès.';
    } else {
        $modele->insert_candidat($_POST);
        $_SESSION['msg'] = 'Candidat ajouté avec succès.';
    }
    header('Location: index.php?page=2');
    exit;

} else {
    $vue = 'Vue/Vue/vue_select_candidats.php';
}