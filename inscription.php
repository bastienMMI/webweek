<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

$message = '';

/**
 * Traitement du formulaire d'inscription
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    // Hachage du mot de passe pour la sécurité
    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'client'; // Rôle par défaut

    $stmt_email = $connection->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
    $stmt_email->execute([':email' => $email]);
    
    if ($stmt_email->fetchColumn() > 0) {
        $message = "Cet email est déjà utilisé.";
    } else {
        $sql = "INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) 
                VALUES (:nom, :prenom, :email, :tel, :pwd, :role)";
        
        $insert = $connection->prepare($sql);
        $success = $insert->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':tel' => $tel,
            ':pwd' => $pwd,
            ':role' => $role
        ]);

        if ($success) {
            header("Location: connexion.php?success=registered");
            exit();
        } else {
            $message = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
    }
}

$page_titre = "Créer un compte — SPA de la Haute-Loire";
$page_description = "Créez votre compte pour pré-réserver un animal et soutenir le refuge de la SPA de la Haute-Loire.";
?>
<!DOCTYPE html>
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
                <h1>Inscription</h1>
            </section>

            <section class="form-inscription-et-connexion">
                <form action="inscription.php" method="POST">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" required>
                        </div>
                        <div class="input-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="input-group">
                        <label for="tel">Téléphone</label>
                        <input type="tel" name="tel" id="tel">
                    </div>

                    <div class="input-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div class="action-group">
                        <button type="submit" class="login-button">S'inscrire</button>
                    <a href="connexion.php" class="inscription-link">Connexion</a>
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