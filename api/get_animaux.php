<?php
header('Content-Type: application/json');
include('../config/configuration.php');
include('../scripts/connection.php');
include('../classes/animal.php'); // On utilise ta classe Manager

$animalManager = new AnimalManager($connection);

// On récupère les filtres envoyés par le JavaScript
$espece = $_GET['espece'] ?? 'tous';
$sexe = $_GET['sexe'] ?? 'tous';
$age_max = (!empty($_GET['age_max'])) ? (int)$_GET['age_max'] : null;

// On utilise ta méthode de classe (Niveau 3)
$animaux = $animalManager->getFiltered($espece, $sexe, $age_max);

// On renvoie le résultat au format JSON
echo json_encode($animaux);