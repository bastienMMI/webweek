<?php
include('../config/configuration.php');
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    // Hachage sécurisé
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'client'; 

    $sql = "INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    
    try {
        $stmt->execute([$nom, $prenom, $email, $tel, $password, $role]);
        header("Location: ../connexion.html?success=1");
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}