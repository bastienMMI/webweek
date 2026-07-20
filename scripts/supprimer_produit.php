<?php
/**
 * Back-office — retrait d'un produit de la boutique.
 * Suppression « douce » : le produit est désactivé pour préserver
 * l'historique des réservations qui le référencent.
 */
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/produit.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

$id_produit = (int)($_GET['id'] ?? 0);

if ($id_produit > 0) {
    $produitManager = new ProduitManager($connection);
    $produitManager->desactiver($id_produit);
}

header("Location: ../admin.php?msg=produit_supprime");
exit();
