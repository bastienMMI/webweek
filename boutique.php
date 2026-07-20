<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/produit.php');

$produitManager = new ProduitManager($connection);
$produits = $produitManager->getActifs();

$connecte = isset($_SESSION['user_id']);

$page_titre = "Boutique solidaire — SPA de la Haute-Loire";
$page_description = "Réservez gratuitement les articles de la boutique solidaire du refuge : retrait et paiement sur place, au profit de nos pensionnaires.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main id="contenu">
        <section class="page-bandeau">
            <p class="eyebrow"><span class="patte" aria-hidden="true"></span>Boutique solidaire</p>
            <h1>Chaque article aide un pensionnaire</h1>
            <p class="page-bandeau-texte">
                Réservez vos articles en ligne, gratuitement et sans paiement :
                nous les mettons de côté, vous les récupérez au refuge.
                L'intégralité des ventes finance les soins de nos animaux.
            </p>
        </section>

        <section class="bandeau-retrait" aria-label="Comment fonctionne la réservation">
            <div class="retrait-etape">
                <span class="retrait-num" aria-hidden="true">1</span>
                <p><strong>Je réserve</strong> mes articles en ligne, sans rien payer.</p>
            </div>
            <div class="retrait-etape">
                <span class="retrait-num" aria-hidden="true">2</span>
                <p><strong>Le refuge prépare</strong> ma réservation et la met de côté.</p>
            </div>
            <div class="retrait-etape">
                <span class="retrait-num" aria-hidden="true">3</span>
                <p><strong>Je retire et je règle sur place</strong>, du lundi au samedi, 13h30&nbsp;–&nbsp;17h30.</p>
            </div>
        </section>

        <div id="boutique-retour" role="status" aria-live="polite"></div>

        <p id="boutique-compteur" class="resultat-compteur" role="status">
            <?= count($produits) ?> article<?= count($produits) > 1 ? 's' : '' ?> dans la boutique
        </p>

        <section class="product-grid" id="product-grid" aria-label="Articles de la boutique">
            <?php if (empty($produits)): ?>
                <p class="empty-msg">La boutique est vide pour le moment. Revenez bientôt !</p>
            <?php else: ?>
                <?php foreach ($produits as $p): ?>
                    <article class="product-card <?= $p['disponible'] ? '' : 'product-epuise' ?>"
                             data-id="<?= (int)$p['id_produit'] ?>">
                        <div class="product-visuel">
                            <img src="images/<?= htmlspecialchars($p['photo']) ?>"
                                 alt="<?= htmlspecialchars($p['nom_produit']) ?>" loading="lazy">
                            <?php if (!$p['disponible']): ?>
                                <span class="badge badge-epuise">Épuisé</span>
                            <?php elseif ((int)$p['stock'] <= 5): ?>
                                <span class="badge badge-stock">Plus que <?= (int)$p['stock'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="product-body">
                            <h3><?= htmlspecialchars($p['nom_produit']) ?></h3>
                            <p class="product-desc"><?= htmlspecialchars($p['description']) ?></p>

                            <div class="product-pied">
                                <span class="product-prix"><?= htmlspecialchars($p['prix_lisible']) ?>
                                    <small>au refuge</small>
                                </span>

                                <?php if (!$p['disponible']): ?>
                                    <span class="product-indispo">Bientôt de retour</span>
                                <?php elseif ($connecte): ?>
                                    <!-- Sans JavaScript, ce formulaire n'est pas soumis :
                                         un lien de repli renvoie vers la page boutique -->
                                    <form class="form-resa-produit"
                                          data-id="<?= (int)$p['id_produit'] ?>"
                                          data-nom="<?= htmlspecialchars($p['nom_produit']) ?>">
                                        <label class="visually-hidden" for="qte-<?= (int)$p['id_produit'] ?>">
                                            Quantité pour <?= htmlspecialchars($p['nom_produit']) ?>
                                        </label>
                                        <input type="number" id="qte-<?= (int)$p['id_produit'] ?>"
                                               name="quantite" value="1" min="1"
                                               max="<?= min((int)$p['stock'], 10) ?>" class="qte-input">
                                        <button type="submit" class="btn-reserver">Réserver</button>
                                    </form>
                                <?php else: ?>
                                    <a href="connexion.php" class="btn-reserver btn-reserver-lien">
                                        Se connecter pour réserver
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <p class="boutique-note">
            Aucun paiement en ligne : la réservation est gratuite et sans engagement.
            Les articles réservés sont gardés une semaine au refuge.
        </p>
    </main>

    <?php include('header et footer/footer.php'); ?>
    <script src="js/boutique_ajax.js" defer></script>
</body>
</html>
