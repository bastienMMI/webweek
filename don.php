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

        <form action="#">
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
            <input type="number" id="montant" placeholder="€" />
          </div>

          <div class="center-btn">
            <button type="submit" class="valider-btn">Valider</button>
          </div>
        </form>
      </div>
    </main>
    <?php 
      include('header et footer/footer.php'); 
    ?>
  </body>
</html>
