<?php
session_start();
require_once('connection.php');
require_once('../classes/don.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $donManager = new DonManager($connection);
    
    if ($donManager->enregistrerDon($_SESSION['user_id'], $_POST['montant'])) {
        header("Location: ../mon-compte.php?msg=don_ok");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement.";
    }
}
?>