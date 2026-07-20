<?php
/**
 * Back-office — mise à jour d'un produit.
 * POST : id_produit, nom, prix, stock, description, photo (optionnelle)
 */
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/produit.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php");
    exit();
}

$id_produit  = (int)($_POST['id_produit'] ?? 0);
$nom         = trim($_POST['nom'] ?? '');
$description = trim($_POST['description'] ?? '');
$prix        = (float)($_POST['prix'] ?? 0);
$stock       = max(0, (int)($_POST['stock'] ?? 0));

if ($id_produit <= 0 || $nom === '' || $prix <= 0) {
    header("Location: ../modifier_produit.php?id=$id_produit&erreur=champs");
    exit();
}

// Nouvelle photo, seulement si un fichier est fourni
$photo = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        $nom_fichier = time() . '_' . preg_replace('/[^a-z0-9._-]/i', '-', basename($_FILES['photo']['name']));
        $destination = dirname(__DIR__) . '/images/boutique/' . $nom_fichier;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $photo = 'boutique/' . $nom_fichier;
        }
    }
}

$produitManager = new ProduitManager($connection);
$produitManager->modifier($id_produit, $nom, $description, $prix, $stock, $photo);

header("Location: ../admin.php?msg=produit_modifie");
exit();
