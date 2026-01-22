<?php
session_start();
include('connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    $id_produit = $_POST['id_produit'];
    $nom_produit = $_POST['nom_produit'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];

    $sql = "UPDATE produit SET nom_produit = ?, prix = ?, description = ? WHERE id_produit = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$nom_produit, $prix, $description, $id_produit]);

    header("Location: ../admin.php?msg=modifie");
    exit();
}
?>