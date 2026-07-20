<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

// Si l'utilisateur est connecté, on récupère ses coordonnées pour les afficher
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $connection->prepare("SELECT nom, prenom, email, telephone FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$erreur = $_GET['erreur'] ?? null;

$page_titre = "Faire un don — SPA Haute-Loire";
$page_description = "Soutenez le refuge de la SPA de la Haute-Loire par un don libre et aidez nos pensionnaires.";
?>
<!doctype html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
  <body>
    <?php include('header et footer/header.php'); ?>
    <main class="don-hero">
      <div class="don-card">
        <h1>Mon don</h1>
        <p>Merci de soutenir notre refuge et nos pensionnaires. Votre générosité fait une réelle différence dans leur vie.</p>

        <?php if ($erreur === 'montant'): ?>
          <p class="message-erreur" role="alert">Merci d'indiquer un montant supérieur à 0 €.</p>
        <?php elseif ($erreur === 'enregistrement'): ?>
          <p class="message-erreur" role="alert">Votre don n'a pas pu être enregistré. Merci de réessayer.</p>
        <?php endif; ?>

        <?php if ($user): ?>
          <form action="scripts/traiter_don.php" method="POST">
            <fieldset class="recap-donateur">
              <legend>Vos coordonnées</legend>
              <p class="recap-info">
                Ces informations proviennent de votre compte et seront associées à votre don.
                Pour les modifier, rendez-vous dans votre espace personnel.
              </p>
              <div class="form-row">
                <div class="input-group">
                  <label for="nom">Nom</label>
                  <input type="text" id="nom" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
                </div>
                <div class="input-group">
                  <label for="prenom">Prénom</label>
                  <input type="text" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
                </div>
              </div>
              <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
              </div>
              <div class="input-group">
                <label for="tel">Téléphone</label>
                <input type="tel" id="tel" value="<?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?>" readonly>
              </div>
            </fieldset>

            <div class="input-group">
              <label for="montant">Montant libre (€)</label>
              <input type="number" id="montant" name="montant" min="1" step="0.01"
                     placeholder="Ex : 20" required aria-describedby="aide-montant">
              <small id="aide-montant">Montant minimum : 1 €.</small>
            </div>

            <button type="submit" class="btn-submit">Valider le don</button>
          </form>
        <?php else: ?>
          <div class="connexion_message">
            <p>Veuillez vous connecter pour soutenir notre refuge.</p>
            <a href="connexion.php" class="valider-btn">Se connecter</a>
          </div>
        <?php endif; ?>
      </div>
    </main>
    <?php include('header et footer/footer.php'); ?>
  </body>
</html>
