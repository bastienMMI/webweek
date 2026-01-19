<?php
header('Content-Type: application/json');
include('../config/configuration.php');
include('connection.php');

$espece = isset($_GET['espece']) ? $_GET['espece'] : 'Tous';

if ($espece == 'Tous') {
    $query = "SELECT * FROM animal WHERE statut = 'disponible' ORDER BY date_arrivee DESC";
    $stmt = $connection->prepare($query);
    $stmt->execute();
} else {
    $query = "SELECT * FROM animal WHERE statut = 'disponible' AND espece = ? ORDER BY date_arrivee DESC";
    $stmt = $connection->prepare($query);
    $stmt->execute([$espece]);
}

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));