<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $statut = $_POST['statut'];

    $sql = "UPDATE animal SET nom = ?, statut = ? WHERE id_animal = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$nom, $statut, $id]);

    header("Location: admin.php?msg=modifie");
}