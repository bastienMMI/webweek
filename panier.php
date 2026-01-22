<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/produit.php');

$produitManager = new ProduitManager($connection);

if (isset($_GET['remove'])) {
    unset($_SESSION['panier'][$_GET['remove']]);
    header('Location: panier.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">
    <?php include('header et footer/head.php'); ?>
        <body>
            <?php include('header et footer/header.php'); ?>
                <main class="container">
                    <h1>Votre Panier</h1>
                    <?php if (empty($_SESSION['panier'])): ?>
                    <p>Votre panier est vide.</p>
                    <?php else: ?>
                    <table class="panier-table">
                        <tr class="table-header">
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    <?php 
                        $total_general = 0;
                        foreach ($_SESSION['panier'] as $id => $quantite): 
                        $p = $produitManager->getProduitById($id); 
                        if ($p):
                            $sous_total = $p['prix'] * $quantite;
                            $total_general += $sous_total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nom_produit']) ?></td>
                            <td><?= number_format($p['prix'], 2) ?> €</td>
                            <td><?= $quantite ?></td>
                            <td><?= number_format($sous_total, 2) ?> €</td>
                            <td><a href="panier.php?remove=<?= $id ?>" style="color: red;">Supprimer</a></td>
                        </tr>
                    <?php 
                        endif;
                        endforeach; 
                    ?>
                    </table>
                <div class="panier-footer" style="margin-top: 20px; text-align: right;">
                    <h3>Total : <?= number_format($total_general, 2) ?> €</h3>
                    <form action="scripts/valider_panier.php" method="POST">
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <button type="submit" class="login-button">Confirmer et commander</button>
                        <?php else: ?>
            <div class="connexion_message">
                <p>
                    Veuillez vous connecter pour acheter
                </p>
                <a href="connexion.php" class="valider-btn" style="text-decoration: none; display: inline-block;">
                    Se connecter
                </a>
            </div>
          <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>
            </main>
        <?php include('header et footer/footer.php'); ?>
    </body>
</html>
          

