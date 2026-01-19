<?php
header('Content-Type: application/json');
include('../config/configuration.php');
include('../scripts/connection.php');

$query = "SELECT * FROM animal";
// Si on veut filtrer par espèce via l'URL : ?espece=Chat
if(isset($_GET['espece'])) {
    $stmt = $connection->prepare("SELECT * FROM animal WHERE espece = ?");
    $stmt->execute([$_GET['espece']]);
    $animaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $animaux = $connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($animaux);