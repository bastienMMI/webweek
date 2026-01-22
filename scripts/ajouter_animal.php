<?php
session_start();
require_once(__DIR__ . '/connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $nom = htmlspecialchars($_POST['nom']);
    $espece = $_POST['espece'];
    $age = (int)$_POST['age'];
    $sexe = $_POST['sexe'];
    $description = htmlspecialchars($_POST['description']);
    $statut = "disponible"; 
    $date_arrivee = date('Y-m-d');

    $dossier_images = dirname(__DIR__) . "/images/animaux/";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        
        // On génère le nom unique
        $nom_image = time() . "_" . basename($_FILES['photo']['name']);
        $destination = $dossier_images . $nom_image;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $sql = "INSERT INTO animal (nom, espece, age, sexe, description, photo, statut, date_arrivee) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $connection->prepare($sql);
            
            try {
                if ($stmt->execute([$nom, $espece, $age, $sexe, $description, $nom_image, $statut, $date_arrivee])) {
                    header("Location: ../admin.php?msg=animal_ajoute");
                    exit();
                }
            } catch (PDOException $e) {
                echo "Erreur SQL : " . $e->getMessage();
            }
        } else {
            echo "Erreur de déplacement du fichier.";
        }
    } else {
        echo "Erreur lors de l'upload de la photo.";
    }
} else {
    echo "Accès refusé.";
}
?>