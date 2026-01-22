<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php");
    exit();
}

$animaux = $connection->query("SELECT * FROM animal ORDER BY id_animal DESC")->fetchAll(PDO::FETCH_ASSOC);
$produits = $connection->query("SELECT * FROM produit ORDER BY id_produit DESC")->fetchAll(PDO::FETCH_ASSOC);
$dons = $connection->query("SELECT * FROM don ORDER BY date_don DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>
<main class="admin-main">
    <div class="title-section">
        <h1>Tableau de Bord Admin</h1>
        <p class="admin-subtitle">Gérez les animaux et les articles de la boutique.</p>
    </div>

    <div class="admin-wrapper">
        <div class="form-section">
            <div class="admin-titre">
                <h2>Les Pensionnaires</h2>
                <a href="ajouter_animal.php" class="login-button">+ Ajouter un animal</a>
            </div>
            <div class="admin-table">
                <?php foreach ($animaux as $a): ?>
                    <div class="animal-row">
                        <div class="item-flex">
                            <img src="images/animaux/<?= $a['photo'] ?>" class="animal-thumb">
                            <div class="item-details">
                                <span class="item-name"><?= htmlspecialchars($a['nom']) ?></span>
                                <span class="item-subtext"><?= $a['espece'] ?></span>
                            </div>
                        </div>
                        <div class="action-group">
                            <a href="modifier_animaux.php?id=<?= $a['id_animal'] ?>" class="edit-btn">Modifier</a>
                            <a href="scripts/supprimer_animal.php?id=<?= $a['id_animal'] ?>" class="delete-btn" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-section">
            <div class="admin-titre">
                <h2>Boutique</h2>
                <a href="ajouter_produit.php" class="login-button btn-add-alt">+ Ajouter un produit</a>
            </div>
            <div class="admin-table">
                <?php foreach ($produits as $p): ?>
                    <div class="animal-row">
                        <div class="item-flex">
                            <img src="images/<?= $p['photo'] ?>" class="animal-thumb">
                            <div class="item-details">
                                <span class="item-name"><?= htmlspecialchars($p['nom_produit']) ?></span>
                                <span class="item-subtext"><?= number_format($p['prix'], 2) ?> €</span>
                            </div>
                        </div>
                        <div class="action-group">
                            <a href="modifier_produit.php?id=<?= $p['id_produit'] ?>" class="edit-btn">Modifier</a>
                            <a href="scripts/supprimer_produit.php?id=<?= $p['id_produit'] ?>" class="delete-btn" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-section">
            <h2>Historique des Dons</h2>
            <div class="admin-table">
                <?php foreach ($dons as $d): ?>
                    <div class="animal-row">
                        <span><?= $d['montant'] ?> € par <?= htmlspecialchars($d['nom']) ?></span>
                        <span>Le <?= $d['date_don'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
<?php include('header et footer/footer.php'); ?>
</body>
</html>