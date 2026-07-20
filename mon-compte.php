<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/reservation.php');

// Sécurité : si l'utilisateur n'est pas connecté, on le renvoie à la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$id_user = $_SESSION['user_id'];

// Récupération des infos de l'utilisateur
$stmt_user = $connection->prepare("SELECT nom, prenom, email, telephone FROM utilisateur WHERE id_utilisateur = ?");
$stmt_user->execute([$id_user]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Pré-réservations de l'utilisateur
$reservationManager = new ReservationManager($connection);
$reservations = $reservationManager->getByUtilisateur($id_user);

// Dons de l'utilisateur
$stmt_dons = $connection->prepare("SELECT montant, date_don FROM don WHERE id_utilisateur = ? ORDER BY date_don DESC");
$stmt_dons->execute([$id_user]);
$dons = $stmt_dons->fetchAll(PDO::FETCH_ASSOC);

$page_titre = "Mon espace — SPA Haute-Loire";
$page_description = "Retrouvez vos pre-reservations d'animaux et vos dons au refuge de la SPA de la Haute-Loire.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main class="container account-container">
        <div class="account-header">
            <h1>Mon espace</h1>
            <p>Bienvenue, <strong><?= htmlspecialchars($user['prenom'] . " " . $user['nom']) ?></strong></p>
        </div>

        <section class="info-utilisateur">
            <h2>Mes informations</h2>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?></p>
        </section>

        <section class="historique">
            <section class="reservation-history">
                <h2>Mes pré-réservations</h2>

                <?php if (empty($reservations)): ?>
                    <p class="empty-msg">Vous n'avez pas encore pré-réservé d'animal.</p>
                    <a href="adopter.php" class="btn-submit">Voir les animaux à l'adoption</a>
                <?php else: ?>
                    <?php foreach ($reservations as $r): ?>
                        <div class="card-commande">
                            <div class="card-header">
                                <span>
                                    <strong><?= htmlspecialchars($r['animal_nom']) ?></strong>
                                    (<?= htmlspecialchars($r['espece']) ?>)
                                    — demande du <?= date('d/m/Y', strtotime($r['date_reservation'])) ?>
                                </span>
                                <span class="status-badge statut-<?= htmlspecialchars($r['statut']) ?>">
                                    <?= htmlspecialchars(ReservationManager::libelleStatut($r['statut'])) ?>
                                </span>
                            </div>

                            <div class="card-body">
                                <?php if (!empty($r['photo'])): ?>
                                    <img src="images/animaux/<?= htmlspecialchars($r['photo']) ?>"
                                         alt="Photo de <?= htmlspecialchars($r['animal_nom']) ?>, <?= htmlspecialchars($r['espece']) ?> à l'adoption"
                                         class="animal-thumb" loading="lazy">
                                <?php endif; ?>
                                <?php if (!empty($r['message'])): ?>
                                    <p class="resa-message"><em>Votre message :</em> <?= nl2br(htmlspecialchars($r['message'])) ?></p>
                                <?php endif; ?>
                            </div>

                            <?php if ($r['statut'] === 'en_attente'): ?>
                                <form action="scripts/annuler_reservation.php" method="POST" class="form-actions">
                                    <input type="hidden" name="id_reservation" value="<?= (int)$r['id_reservation'] ?>">
                                    <button type="submit" class="btn-cancel"
                                            onclick="return confirm('Annuler cette pré-réservation ?');">
                                        Annuler ma demande
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>

            <section>
                <h2>Mes dons</h2>
                <?php if (empty($dons)): ?>
                    <p>Aucun don effectué.</p>
                <?php else: ?>
                    <?php foreach ($dons as $d): ?>
                        <div class="card-commande">
                            <h3>Don de <?= number_format($d['montant'], 2, ',', ' ') ?> €</h3>
                            <p>Le <?= date('d/m/Y', strtotime($d['date_don'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </section>
    </main>

    <?php include('header et footer/footer.php'); ?>
</body>
</html>
