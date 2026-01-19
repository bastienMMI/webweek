<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

// Sécurité : Seul l'admin accède à cette page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php");
    exit();
}

// Récupération de tous les animaux
$animaux = $connection->query("SELECT * FROM animal ORDER BY id_animal DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
    <?php 
  include('header et footer/head.php'); 
?>
<body>
<?php 
  include('header et footer/header.php'); 
?>

    <main class="admin-container">
        <div class="admin-header">
            <h1>Gestion du Refuge</h1>
            <a href="ajouter_animal.php" class="btn-add">+ Ajouter un Animal</a>
            <a href="index.php" class="btn-back">Retour au site</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animaux as $a): ?>
                <tr>
                    <td><img src="images/animaux/<?= $a['photo'] ?>" style="width:50px; height:50px; object-fit:cover; border-radius:5px;"></td>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= $a['espece'] ?></td>
                    <td><span class="badge"><?= $a['statut'] ?></span></td>
                    <td>
                        <a href="gerer_animaux.php?id=<?= $a['id_animal'] ?>" class="btn-edit">Modifier</a>
                        <a href="scripts/suppression_animal.php?id=<?= $a['id_animal'] ?>" 
   onclick="return confirm('Es-tu sûr ?')">
   Supprimer
</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
<?php 
  include('header et footer/footer.php'); 
?>
</body>
</html>