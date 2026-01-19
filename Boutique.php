<?php
include('config/configuration.php');
include('scripts/connection.php');

// 1. Récupération des produits depuis la table 'produit'
$query = "SELECT * FROM produit ORDER BY id_produit DESC";
$results = $connection->query($query);
$produits = $results->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
    <?php 
  include('header et footer/head.php'); 
?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main>
      <section class="product-gallery">
            <h1>Notre Boutique</h1>
        <div class="gallery">
                <?php foreach ($produits as $p): ?>
                <article class="product-card">
                    <img src="images/<?= htmlspecialchars($p['photo']) ?>" 
                         alt="<?= htmlspecialchars($p['nom_produit']) ?>" loading="lazy" />
                    <div class="info">
                        <h3><?= htmlspecialchars($p['nom_produit']) ?></h3>
                        <div class="price"><?= number_format($p['prix'], 2) ?> €</div>
                        <p class="desc"><?= htmlspecialchars($p['description']) ?></p>
                        
                        <?php if($p['stock'] > 0): ?>
                            <button class="btn-buy">Ajouter au panier</button>
                        <?php else: ?>
                            <span class="out-of-stock">Rupture de stock</span>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include('header et footer/footer.php'); ?>
</body>
</html>