<?php
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/animal.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $animalManager = new AnimalManager($connection);

    // La suppression retourne le nom de la photo, pour pouvoir la retirer du disque
    $photo = $animalManager->supprimer($id);

    if ($photo) {
        $chemin = dirname(__DIR__) . "/images/animaux/" . basename($photo);
        if (is_file($chemin)) {
            @unlink($chemin);
        }
    }
}

header("Location: ../admin.php?msg=supprime");
exit();
