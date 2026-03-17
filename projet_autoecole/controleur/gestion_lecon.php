<?php
$laLecon      = null;
$lesLecons    = $modele->selectAll_lecons();
$lesCandidats = $modele->selectAll_candidats();
$lesMoniteurs = $modele->selectAll_moniteurs();
$lesVoitures  = $modele->selectAll_voitures();

if ($action === 'add') {
    $vue = 'Vue/Vue/vue_insert_lecon.php';

} elseif ($action === 'edit') {
    $laLecon = $modele->selectWhere_lecon($_GET['idlecon']);
    $vue = 'Vue/Vue/vue_insert_lecon.php';

} elseif ($action === 'sup') {
    $modele->delete_lecon($_GET['idlecon']);
    $_SESSION['msg'] = 'Leçon supprimée.';
    header('Location: index.php?page=4');
    exit;

} elseif (isset($_POST['Valider'])) {
    try {
        $modele->insert_lecon($_POST);
        $_SESSION['msg'] = 'Leçon planifiée avec succès.';
    } catch (Exception $e) {
        $_SESSION['msg']      = '⚠️ ' . $e->getMessage();
        $_SESSION['msg_type'] = 'warning';
    }
    header('Location: index.php?page=4');
    exit;

} elseif (isset($_POST['Modifier'])) {
    try {
        $modele->update_lecon($_POST);
        $_SESSION['msg'] = 'Leçon modifiée avec succès.';
    } catch (Exception $e) {
        $_SESSION['msg']      = '⚠️ ' . $e->getMessage();
        $_SESSION['msg_type'] = 'warning';
    }
    header('Location: index.php?page=4');
    exit;

} else {
    $vue = 'Vue/Vue/vue_select_lecons.php';
}
