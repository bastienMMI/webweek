<?php
header('Content-Type: application/json');
include('../config/configuration.php');
include('../scripts/connection.php');

$sql = "SELECT nom, espece, photo, statut FROM animal WHERE statut = 'disponible'";
$stmt = $connection->query($sql);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));