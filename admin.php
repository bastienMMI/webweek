<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

// Sécurité : Seul l'admin accède à cette page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php");
    exit();
}

// Récupération de tous les animaux et produits de la boutique
$animaux = $connection->query("SELECT * FROM animal ORDER BY id_animal DESC")->fetchAll(PDO::FETCH_ASSOC);
$produits = $connection->query("SELECT * FROM produit ORDER BY id_produit DESC")->fetchAll(PDO::FETCH_ASSOC);
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
<main class="admin-main">
   
        <div class="title-section">
            <h1>Tableau de Bord Admin</h1>
            <p class="admin-subtitle">Gérez les animaux et les articles de la boutique.</p>
        </div>
 <div class="admin-wrapper">
        <div class="form-section">
            <div class=admin-titre>
                <h2>Les Pensionnaires</h2>
                <a href="ajouter_animal.php" class="login-button" style="text-decoration:none;">+ Ajouter un animal</a>
            </div>
            <div class="admin-table">
                <?php foreach ($animaux as $a): ?>
                <div class="animal-row">
                    <div style="display: flex; align-items: center;">
                        <img src="images/animaux/<?= $a['photo'] ?>" class="animal-thumb">
                        <div class="animal-info">
                            <span class="animal-name"><?= htmlspecialchars($a['nom']) ?></span>
                            <span class="animal-specie"><?= $a['espece'] ?> — <strong><?= $a['statut'] ?></strong></span>
                        </div>
                    </div>
                    <div class="action-group">
                        <a href="modifier_animaux.php?id=<?= $a['id_animal'] ?>" class="edit-btn">Modifier</a>
                        <a href="scripts/supprimer_animal.php?id=<?= $a['id_animal'] ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet animal ?');">Supprimer</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-section" style="margin-top:40px;">
            <div class="admin-titre">
                <h2>Boutique</h2>
                <a href="ajouter_produit.php" class="login-button" style="text-decoration:none; background-color:var(--green-dark);">+ Ajouter un produit</a>
            </div>
            <div class="admin-table">
                <?php foreach ($produits as $p): ?>
                    <div class="animal-row">
                        <div style="display: flex; align-items: center;">
                            <img src="images/<?= $p['photo'] ?>" class="animal-thumb">
                            <div class="animal-info">
                                <span class="animal-name"><?= htmlspecialchars($p['nom_produit']) ?></span>
                                <span class="animal-specie"><?= number_format($p['prix'], 2, ',', ' ') ?> €</span>
                            </div>
                        </div>
                        <div class="action-group">
                            <a href="modifier_produit.php?id=<?= $p['id_produit'] ?>" class="edit-btn">Modifier</a>
                            <a href="scripts/supprimer_produit.php?id=<?= $p['id_produit'] ?>" class="delete-btn" onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
<?php 
  include('header et footer/footer.php'); 
?>
</body>
</html>