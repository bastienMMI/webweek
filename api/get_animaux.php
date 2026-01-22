<?php
header('Content-Type: application/json');
include('../config/configuration.php');
include('../scripts/connection.php');
include('../classes/animal.php');

$animalManager = new AnimalManager($connection);

$espece = $_GET['espece'] ?? 'tous';
$sexe = $_GET['sexe'] ?? 'tous';
$age_max = (!empty($_GET['age_max'])) ? (int)$_GET['age_max'] : null;

$animaux = $animalManager->getFiltered($espece, $sexe, $age_max);

// On renvoie le résultat au format JSON
echo json_encode($animaux);