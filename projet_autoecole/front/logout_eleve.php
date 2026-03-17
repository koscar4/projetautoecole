<?php
session_start();
unset($_SESSION['eleve_id'], $_SESSION['eleve_nom']);
session_destroy();
header('Location: login.php');
exit;
