<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $connection->prepare("SELECT * FROM animal WHERE id_animal = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    header("Location: admin.php?erreur=introuvable");
    exit();
}

$erreur = $_GET['erreur'] ?? null;

$page_titre = "Modifier " . $animal['nom'] . " — Administration SPA Haute-Loire";
$page_description = "Mise à jour de la fiche d'un pensionnaire du refuge.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="section-contact">
    <div class="admin-edit-container">
        <h1 class="contact-main-title">Modifier un animal</h1>
        <p>Mise à jour de : <strong><?= htmlspecialchars($animal['nom']) ?></strong></p>

        <?php if ($erreur === 'donnees'): ?>
            <p class="message-erreur" role="alert">Merci de vérifier le nom, l'espèce, le sexe et le statut.</p>
        <?php endif; ?>

        <form action="scripts/update_animal.php" method="POST">
            <input type="hidden" name="id" value="<?= (int)$animal['id_animal'] ?>">

            <div class="edit-grid">
                <div class="edit-group">
                    <label for="nom">Nom de l'animal</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($animal['nom']) ?>" required>
                </div>

                <div class="edit-group">
                    <label for="espece">Espèce</label>
                    <select id="espece" name="espece" required>
                        <?php foreach (['chien' => 'Chien', 'chat' => 'Chat', 'nac' => 'NAC', 'autre' => 'Autre'] as $val => $lib): ?>
                            <option value="<?= $val ?>" <?= $animal['espece'] === $val ? 'selected' : '' ?>><?= $lib ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="edit-group">
                    <label for="statut">Statut</label>
                    <select id="statut" name="statut" required>
                        <?php foreach (['disponible' => 'Disponible', 'reserve' => 'Réservé', 'adopte' => 'Adopté'] as $val => $lib): ?>
                            <option value="<?= $val ?>" <?= $animal['statut'] === $val ? 'selected' : '' ?>><?= $lib ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="edit-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance"
                           value="<?= htmlspecialchars($animal['date_naissance'] ?? '') ?>"
                           max="<?= date('Y-m-d') ?>" aria-describedby="aide-naissance">
                    <small id="aide-naissance">L'âge est calculé automatiquement. Laissez vide si inconnue.</small>
                </div>

                <div class="edit-group">
                    <label for="sexe">Sexe</label>
                    <select id="sexe" name="sexe" required>
                        <?php foreach (['masculin' => 'Mâle', 'feminin' => 'Femelle'] as $val => $lib): ?>
                            <option value="<?= $val ?>" <?= $animal['sexe'] === $val ? 'selected' : '' ?>><?= $lib ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <fieldset class="edit-group full-width">
                    <legend>Suivi sanitaire</legend>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="vaccine" value="1" <?= !empty($animal['vaccine']) ? 'checked' : '' ?>> Vacciné
                    </label>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="sterilise" value="1" <?= !empty($animal['sterilise']) ? 'checked' : '' ?>> Stérilisé / castré
                    </label>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="identifie" value="1" <?= !empty($animal['identifie']) ? 'checked' : '' ?>> Identifié (puce ou tatouage)
                    </label>
                </fieldset>

                <div class="edit-group full-width">
                    <label for="description">Description / histoire de l'animal</label>
                    <textarea id="description" name="description" rows="5"><?= htmlspecialchars($animal['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="login-button btn-save">Sauvegarder</button>
                <a href="admin.php" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</main>
<?php include('header et footer/footer.php'); ?>
</body>
</html>
