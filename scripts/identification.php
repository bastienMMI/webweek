<?php
session_start();
include('../config/configuration.php');
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connection->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['prenom'] = $user['prenom'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin.php");
        } else {
            header("Location: ../index.php");
        }
    } else {
        header("Location: ../connexion.php?error=1");
    }
}