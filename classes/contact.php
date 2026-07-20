<?php
/**
 * Gestion des messages envoyés depuis le formulaire de contact.
 */
class MessageContactManager {

    private $db;

    /**
     * Longueur d'une chaîne, avec repli si l'extension mbstring
     * n'est pas disponible sur le serveur d'hébergement.
     */
    private static function longueur($texte) {
        $texte = (string)$texte;
        return function_exists('mb_strlen') ? mb_strlen($texte, 'UTF-8') : strlen($texte);
    }

    public function __construct($connection) {
        $this->db = $connection;
    }

    /** Valide les données du formulaire. Retourne la liste des erreurs. */
    public function valider(array $d) {
        $erreurs = [];

        if (self::longueur(trim($d['nom'] ?? '')) < 2) {
            $erreurs[] = "Merci d'indiquer votre nom.";
        }
        if (!filter_var($d['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'adresse e-mail est invalide.";
        }
        if (self::longueur(trim($d['message'] ?? '')) < 10) {
            $erreurs[] = "Le message doit faire au moins 10 caractères.";
        }
        if (!empty($d['telephone']) && !preg_match('/^[0-9 .+()-]{6,20}$/', $d['telephone'])) {
            $erreurs[] = "Le numéro de téléphone est invalide.";
        }
        return $erreurs;
    }

    /** Enregistre un message. Retourne son identifiant. */
    public function enregistrer(array $d) {
        $sql = "INSERT INTO message_contact (nom, email, telephone, objet, message)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            trim($d['nom']),
            trim($d['email']),
            !empty($d['telephone']) ? trim($d['telephone']) : null,
            !empty($d['objet']) ? trim($d['objet']) : null,
            trim($d['message']),
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Tous les messages, les non traités en premier. */
    public function getAll() {
        $stmt = $this->db->query(
            "SELECT * FROM message_contact ORDER BY traite ASC, date_envoi DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Nombre de messages en attente de traitement. */
    public function compterNonTraites() {
        return (int)$this->db->query("SELECT COUNT(*) FROM message_contact WHERE traite = 0")->fetchColumn();
    }

    /** Marque un message comme traité ou non traité. */
    public function marquerTraite($id, $traite = true) {
        $stmt = $this->db->prepare("UPDATE message_contact SET traite = ? WHERE id_message = ?");
        return $stmt->execute([$traite ? 1 : 0, (int)$id]);
    }
}
