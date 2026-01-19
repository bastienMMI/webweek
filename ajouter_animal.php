<!DOCTYPE html>
<html lang="fr">
    <?php 
  include('header et footer/head.php'); 
?>
<body>
    <?php 
        include('header et footer/header.php'); 
    ?>
    <div class="form-container">
        <h1>Ajouter un nouvel animal</h1>
        <form action="scripts/ajouter_animal.php" method="POST" enctype="multipart/form-data">
            <label>Nom</label>
            <input type="text" name="nom" required>
            
            <label>Espèce</label>
            <select name="espece">
                <option value="Chien">Chien</option>
                <option value="Chat">Chat</option>
                <option value="NAC">NAC</option>
            </select>

            <label>Âge</label>
            <input type="number" name="age" required>

            <label>Sexe</label>
            <select name="sexe">
                <option value="Mâle">Mâle</option>
                <option value="Femelle">Femelle</option>
            </select>

            <label>Description</label>
            <textarea name="description"></textarea>

            <label>Photo</label>
            <input type="file" name="photo" accept="image/*" required>

            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>
    </div>

    <?php 
        include('header et footer/footer.php'); 
    ?>
</body>
</html>