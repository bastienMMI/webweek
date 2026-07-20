<?php
$page_titre = "Connexion — SPA de la Haute-Loire";
$page_description = "Connectez-vous à votre espace personnel pour suivre vos pré-réservations et vos dons au refuge.";
?>
<!doctype html>
<html lang="fr">

    <?php 
  include('header et footer/head.php'); 
?>

<body>
  <?php 
    include('header et footer/header.php'); 
  ?>

  <main class="connexion-main">
    <div class="connexion-wrapper">
      <section class="title-section">
        <h1>Connexion</h1>
      </section>

      <section class="form-inscription-et-connexion">
        <form action="scripts/identification.php" method="POST">
          <div class="input-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required />
          </div>
          <div class="input-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required />
          </div>
          <div class="action-group">
            <button type="submit" class="login-button">Connexion</button>
            <a href="inscription.php" class="inscription-link">Inscription</a>
         </div>
    </div>
    </form>
    </section>
    </div>
  </main>
  <?php 
    include('header et footer/footer.php'); 
  ?>
</body>

</html>