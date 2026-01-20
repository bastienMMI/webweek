<?php 
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php");
    exit();
}

include('header et footer/head.php'); 
?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="section-contact">
    <div class="admin-edit-container">
        <h1 class="contact-main-title">Nouvel Animal</h1>
        <p>Ajoutez un nouveau pensionnaire au catalogue du refuge.</p>
        
        <form action="scripts/ajouter_animal.php" method="POST" enctype="multipart/form-data">
            <div class="edit-grid">
                <div class="edit-group">
                    <label>Nom de l'animal</label>
                    <input type="text" name="nom" required>
                </div>

                <div class="edit-group">
                    <label>Espèce</label>
                    <select name="espece">
                        <option value="Chien">Chien</option>
                        <option value="Chat">Chat</option>
                        <option value="NAC">NAC</option>
                    </select>
                </div>

                <div class="edit-group">
                    <label>Âge</label>
                    <input type="number" name="age">
                </div>

                <div class="edit-group">
                    <label>Sexe</label>
                    <select name="sexe">
                        <option value="masculin">Masculin</option>
                        <option value="feminin">Féminin</option>
                    </select>
                </div>

                <div class="edit-group">
                    <label>Photo</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>

                <div class="edit-group full-width">
                    <label>Description</label>
                    <textarea name="description" rows="4"></textarea>
                </div>
            </div>

<div class="form-actions">
    <button type="submit" class="login-button btn-save">Ajouter l'animal</button>
    <a href="admin.php" class="btn-cancel">Annuler</a>
</div>
            </div>
        </form>
    </div>
</main>
<?php include('header et footer/footer.php'); ?>
</body>