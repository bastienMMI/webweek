<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/animal.php');
include('classes/reservation.php');
include('classes/contact.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$animalManager      = new AnimalManager($connection);
$reservationManager = new ReservationManager($connection);
$messageManager     = new MessageContactManager($connection);

$animaux      = $animalManager->getAllAnimaux();
$stats        = $animalManager->statistiques();
$reservations = $reservationManager->getAll();
$messages     = $messageManager->getAll();
$non_traites  = $messageManager->compterNonTraites();

$dons = $connection->query("SELECT * FROM don ORDER BY date_don DESC")->fetchAll(PDO::FETCH_ASSOC);

$messages_retour = [
    'modifie'       => "L'animal a bien été mis à jour.",
    'animal_ajoute' => "L'animal a bien été ajouté.",
    'supprime'      => "L'animal a bien été supprimé.",
    'resa_traitee'  => "La réservation a bien été mise à jour.",
    'msg_traite'    => "Le message a bien été mis à jour.",
];
$msg = $_GET['msg'] ?? null;

$page_titre = "Tableau de bord — Administration SPA Haute-Loire";
$page_description = "Espace d'administration du refuge de la SPA de la Haute-Loire.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="admin-main">
    <div class="title-section">
        <h1>Tableau de bord</h1>
        <p class="admin-subtitle">Gérez les pensionnaires, les demandes de pré-réservation, les dons et les messages.</p>
    </div>

    <?php if ($msg && isset($messages_retour[$msg])): ?>
        <p class="message-succes" role="status"><?= htmlspecialchars($messages_retour[$msg]) ?></p>
    <?php endif; ?>

    <!-- Quelques chiffres clés -->
    <section class="admin-stats" aria-label="Chiffres clés">
        <div class="stat-card"><span class="stat-nombre"><?= $stats['total'] ?></span> pensionnaires</div>
        <div class="stat-card"><span class="stat-nombre"><?= $stats['disponible'] ?></span> disponibles</div>
        <div class="stat-card"><span class="stat-nombre"><?= $stats['reserve'] ?></span> réservés</div>
        <div class="stat-card"><span class="stat-nombre"><?= $stats['adopte'] ?></span> adoptés</div>
        <div class="stat-card"><span class="stat-nombre"><?= $non_traites ?></span> messages en attente</div>
    </section>

    <div class="admin-wrapper">

        <!-- ---------------- Animaux ---------------- -->
        <section class="form-section">
            <div class="admin-titre">
                <h2>Les pensionnaires</h2>
                <a href="ajouter_animal.php" class="login-button">+ Ajouter un animal</a>
            </div>

            <div class="admin-table">
                <?php if (empty($animaux)): ?>
                    <p class="empty-msg">Aucun animal enregistré.</p>
                <?php else: ?>
                    <?php foreach ($animaux as $a): ?>
                        <div class="animal-row">
                            <div class="item-flex">
                                <img src="images/animaux/<?= htmlspecialchars($a['photo']) ?>"
                                     alt="<?= htmlspecialchars($a['alt']) ?>" class="animal-thumb" loading="lazy">
                                <div class="item-details">
                                    <span class="item-name"><?= htmlspecialchars($a['nom']) ?></span>
                                    <span class="item-subtext">
                                        <?= htmlspecialchars($a['espece_label']) ?> ·
                                        <?= htmlspecialchars($a['sexe_label']) ?> ·
                                        <?= htmlspecialchars($a['age_lisible']) ?> ·
                                        <?= htmlspecialchars($a['statut_label']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="action-group">
                                <a href="modifier_animaux.php?id=<?= (int)$a['id_animal'] ?>" class="edit-btn">Modifier</a>
                                <a href="scripts/supprimer_animal.php?id=<?= (int)$a['id_animal'] ?>" class="delete-btn"
                                   onclick="return confirm('Supprimer définitivement <?= htmlspecialchars(addslashes($a['nom'])) ?> ?');">Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- ---------------- Réservations ---------------- -->
        <section class="form-section">
            <h2>Demandes de pré-réservation</h2>

            <div class="admin-table">
                <?php if (empty($reservations)): ?>
                    <p class="empty-msg">Aucune demande pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($reservations as $r): ?>
                        <div class="animal-row resa-row">
                            <div class="item-details">
                                <span class="item-name">
                                    <?= htmlspecialchars($r['animal_nom']) ?>
                                    <span class="status-badge statut-<?= htmlspecialchars($r['statut']) ?>">
                                        <?= htmlspecialchars(ReservationManager::libelleStatut($r['statut'])) ?>
                                    </span>
                                </span>
                                <span class="item-subtext">
                                    Demande de <?= htmlspecialchars($r['user_prenom'] . ' ' . $r['user_nom']) ?>
                                    (<?= htmlspecialchars($r['email']) ?><?= $r['telephone'] ? ' · ' . htmlspecialchars($r['telephone']) : '' ?>)
                                    le <?= date('d/m/Y', strtotime($r['date_reservation'])) ?>
                                </span>
                                <?php if (!empty($r['message'])): ?>
                                    <span class="item-subtext resa-message">« <?= htmlspecialchars($r['message']) ?> »</span>
                                <?php endif; ?>
                            </div>

                            <?php if (in_array($r['statut'], ['en_attente', 'confirmee'], true)): ?>
                                <div class="action-group">
                                    <?php if ($r['statut'] === 'en_attente'): ?>
                                        <form action="scripts/traiter_reservation.php" method="POST" class="inline-form">
                                            <input type="hidden" name="id_reservation" value="<?= (int)$r['id_reservation'] ?>">
                                            <input type="hidden" name="statut" value="confirmee">
                                            <button type="submit" class="edit-btn">Confirmer</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="scripts/traiter_reservation.php" method="POST" class="inline-form">
                                            <input type="hidden" name="id_reservation" value="<?= (int)$r['id_reservation'] ?>">
                                            <input type="hidden" name="statut" value="concretisee">
                                            <button type="submit" class="edit-btn">Adoption réalisée</button>
                                        </form>
                                    <?php endif; ?>

                                    <form action="scripts/traiter_reservation.php" method="POST" class="inline-form">
                                        <input type="hidden" name="id_reservation" value="<?= (int)$r['id_reservation'] ?>">
                                        <input type="hidden" name="statut" value="annulee">
                                        <button type="submit" class="delete-btn"
                                                onclick="return confirm('Annuler cette demande ? L\'animal redeviendra disponible.');">Annuler</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- ---------------- Messages ---------------- -->
        <section class="form-section">
            <h2>Messages de contact<?= $non_traites > 0 ? " ($non_traites en attente)" : "" ?></h2>

            <div class="admin-table">
                <?php if (empty($messages)): ?>
                    <p class="empty-msg">Aucun message reçu.</p>
                <?php else: ?>
                    <?php foreach ($messages as $m): ?>
                        <div class="animal-row <?= $m['traite'] ? 'message-traite' : '' ?>">
                            <div class="item-details">
                                <span class="item-name">
                                    <?= htmlspecialchars($m['objet'] ?: 'Sans objet') ?>
                                    <?php if (!$m['traite']): ?>
                                        <span class="status-badge statut-en_attente">Nouveau</span>
                                    <?php endif; ?>
                                </span>
                                <span class="item-subtext">
                                    <?= htmlspecialchars($m['nom']) ?> ·
                                    <a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a>
                                    <?= $m['telephone'] ? ' · ' . htmlspecialchars($m['telephone']) : '' ?>
                                    · le <?= date('d/m/Y à H\hi', strtotime($m['date_envoi'])) ?>
                                </span>
                                <p class="item-message"><?= nl2br(htmlspecialchars($m['message'])) ?></p>
                            </div>
                            <div class="action-group">
                                <form action="scripts/marquer_message.php" method="POST" class="inline-form">
                                    <input type="hidden" name="id_message" value="<?= (int)$m['id_message'] ?>">
                                    <input type="hidden" name="traite" value="<?= $m['traite'] ? '0' : '1' ?>">
                                    <button type="submit" class="edit-btn">
                                        <?= $m['traite'] ? 'Rouvrir' : 'Marquer traité' ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- ---------------- Dons ---------------- -->
        <section class="form-section">
            <h2>Historique des dons</h2>
            <div class="admin-table">
                <?php if (empty($dons)): ?>
                    <p class="empty-msg">Aucun don enregistré.</p>
                <?php else: ?>
                    <?php foreach ($dons as $d): ?>
                        <div class="animal-row">
                            <span><?= number_format($d['montant'], 2, ',', ' ') ?> € par <?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></span>
                            <span>Le <?= date('d/m/Y', strtotime($d['date_don'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php include('header et footer/footer.php'); ?>
</body>
</html>
