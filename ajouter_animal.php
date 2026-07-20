<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$erreurs = [
    'donnees'     => "Merci de vérifier le nom, l'espèce et le sexe de l'animal.",
    'photo'       => "La photo est obligatoire.",
    'photo_type'  => "La photo doit être au format JPEG, PNG ou WebP et peser moins de 3 Mo.",
    'upload'      => "L'enregistrement de la photo a échoué.",
    'sql'         => "L'ajout en base a échoué. Merci de réessayer.",
];
$erreur = $_GET['erreur'] ?? null;

$page_titre = "Ajouter un animal — Administration SPA Haute-Loire";
$page_description = "Formulaire d'ajout d'un pensionnaire au catalogue du refuge.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="section-contact">
    <div class="admin-edit-container">
        <h1 class="contact-main-title">Nouvel animal</h1>
        <p>Ajoutez un nouveau pensionnaire au catalogue du refuge.</p>

        <?php if ($erreur && isset($erreurs[$erreur])): ?>
            <p class="message-erreur" role="alert"><?= htmlspecialchars($erreurs[$erreur]) ?></p>
        <?php endif; ?>

        <form action="scripts/ajouter_animal.php" method="POST" enctype="multipart/form-data">
            <div class="edit-grid">
                <div class="edit-group">
                    <label for="nom">Nom de l'animal</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="edit-group">
                    <label for="espece">Espèce</label>
                    <select id="espece" name="espece" required>
                        <option value="chien">Chien</option>
                        <option value="chat">Chat</option>
                        <option value="nac">NAC</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="edit-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance"
                           max="<?= date('Y-m-d') ?>" aria-describedby="aide-naissance">
                    <small id="aide-naissance">L'âge sera calculé automatiquement. Laissez vide si inconnue.</small>
                </div>

                <div class="edit-group">
                    <label for="sexe">Sexe</label>
                    <select id="sexe" name="sexe" required>
                        <option value="masculin">Mâle</option>
                        <option value="feminin">Femelle</option>
                    </select>
                </div>

                <div class="edit-group">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required>
                </div>

                <fieldset class="edit-group full-width">
                    <legend>Suivi sanitaire</legend>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="vaccine" value="1"> Vacciné
                    </label>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="sterilise" value="1"> Stérilisé / castré
                    </label>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="identifie" value="1"> Identifié (puce ou tatouage)
                    </label>
                </fieldset>

                <div class="edit-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="login-button btn-save">Ajouter l'animal</button>
                <a href="admin.php" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</main>
<?php include('header et footer/footer.php'); ?>
</body>
</html>
