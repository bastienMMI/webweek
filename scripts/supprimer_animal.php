<?php
session_start();
include('connection.php');

if ($_SESSION['role'] === 'admin' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Trouver le nom de la photo
    $stmt = $connection->prepare("SELECT photo FROM animal WHERE id_animal = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();

    // 2. Supprimer l'image du dossier
    if ($res) {
        unlink("../images/animaux/" . $res['photo']);
    }

    // 3. Supprimer de la BDD
    $del = $connection->prepare("DELETE FROM animal WHERE id_animal = ?");
    $del->execute([$id]);
}
header("Location: ../admin.php");