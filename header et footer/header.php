<header>
    <input type="checkbox" id="menu-toggle" class="menu-checkbox">

    <label for="menu-toggle" class="burger-menu">
        <span></span><span></span><span></span>
    </label>

    <div class="logo">
        <a href="index.php"><img src="images/Logo-1.webp" alt="Logo SPA Haute-Loire"></a>
    </div>

    <nav>
        <ul>
            <li><a href="adopter.php">Adopter</a></li>
            <li><a href="aider.php">Nous aider</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="boutique.php">Boutique</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="mobile-only"><a href="mon-compte.php">Mon Compte</a></li>
                <li class="mobile-only"><a href="scripts/deconnexion.php" style="color: #ff4d4d;">Déconnexion</a></li>
            <?php else: ?>
                <li class="mobile-only"><a href="connexion.php">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>

<div class="user-access">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="mon-compte.php" class="btn-profil">Mon Compte</a>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="btn-admin" style="background: #ab8a60; color: white; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-weight: bold;">Admin</a>
            <?php endif; ?>
            
            <a href="scripts/deconnexion.php" class="btn-deco">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" class="btn-connexion-desktop">Connexion</a>
        <?php endif; ?>
    </div>
</header>