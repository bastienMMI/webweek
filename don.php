<!doctype html>
<html lang="fr">
    <?php 
  include('header et footer/head.php'); 
?>

  <body>
    <?php 
      include('header et footer/header.php'); 
    ?>
    <main class="don-hero">
      <div class="don-card">
        <h1>Mon Don</h1>
        <p>Merci de soutenir notre refuge et nos pensionnaires. Votre générosité fait une réelle différence dans leur vie.</p>
        <form action="scripts/traiter_don.php" method="POST">
          <div class="form-row">
            <div class="input-group">
              <label for="nom">Nom</label>
              <input type="text" id="nom" required />
            </div>
            <div class="input-group">
              <label for="prenom">Prénom</label>
              <input type="text" id="prenom" required />
            </div>
          </div>

          <div class="input-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" required />
          </div>

          <div class="input-group">
            <label for="tel">Téléphone</label>
            <input type="tel" id="tel" />
          </div>

          <div class="input-group">
            <label for="montant">Montant Libre</label>
            <input type="number" name="montant" placeholder="€" required />       
            <?php if (isset($_SESSION['user_id'])): ?>
            <button type="submit" class="btn-submit">Valider le don</button>
          <?php else: ?>
            <div class="connexion_message">
                <p>
                    Veuillez vous connecter pour soutenir notre refuge
                </p>
                <a href="connexion.php" class="valider-btn" style="text-decoration: none; display: inline-block;">
                    Se connecter
                </a>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </main>
    <?php 
      include('header et footer/footer.php'); 
    ?>
  </body>
</html>
