<?php
include(__DIR__ . '/../config/configuration.php'); 

try {
    $connection = new PDO(
        "mysql:host=$hote;dbname=$nom_bdd", 
        $utilisateur, 
        $mot_de_passe, 
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $encodage)
    );

    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Serveur actuellement inaccessible. Erreur : " . $e->getMessage());
}
?>