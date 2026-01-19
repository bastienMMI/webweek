<?php
// racine/modifier_animal.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: connexion.html"); exit(); }

include('../config/configuration.php');
include('../scripts/connection.php');

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM animal WHERE id_animal = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();
?>
<form action="scripts/update_animal.php" method="POST"> 
<?php
// scripts/update_animal.php
session_start();
include('../scipts/connection.php'); // Pas besoin de config car connection.php l'inclut déjà

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $statut = $_POST['statut'];

    $sql = "UPDATE animal SET nom = ?, statut = ? WHERE id_animal = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$nom, $statut, $id]);

    // On retourne à la page admin avec un petit message de succès
    header("Location: ../admin.php?msg=modifie");
    exit();
}