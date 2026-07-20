<?php
session_start();
require_once('connection.php');
require_once('../classes/don.php');

// Seul un utilisateur connecté peut faire un don (ses coordonnées sont reprises du compte)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../don.php");
    exit();
}

// Validation du montant côté serveur : doit être numérique et strictement positif
$montant = str_replace(',', '.', trim($_POST['montant'] ?? ''));

if (!is_numeric($montant) || (float)$montant <= 0) {
    header("Location: ../don.php?erreur=montant");
    exit();
}

$montant = round((float)$montant, 2);

$donManager = new DonManager($connection);

if ($donManager->enregistrerDon($_SESSION['user_id'], $montant)) {
    header("Location: ../mon-compte.php?msg=don_ok");
    exit();
}

header("Location: ../don.php?erreur=enregistrement");
exit();
