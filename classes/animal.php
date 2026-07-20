<?php
/**
 * Couche métier des animaux du refuge.
 * Centralise tous les accès à la table `animal` : consultation, filtrage,
 * création, mise à jour et suppression. Toutes les requêtes sont préparées.
 */
class AnimalManager {

    private $db;

    /** Valeurs autorisées, alignées sur les ENUM de la base. */
    const ESPECES = ['chien', 'chat', 'nac', 'autre'];
    const SEXES   = ['masculin', 'feminin'];
    const STATUTS = ['disponible', 'reserve', 'adopte'];

    public function __construct($connection) {
        $this->db = $connection;
    }

    /* ------------------------------------------------------------------
       Helpers d'affichage
       ------------------------------------------------------------------ */

    /** Âge en années révolues, calculé depuis la date de naissance. */
    public static function calculerAge($date_naissance) {
        if (empty($date_naissance)) {
            return null;
        }
        try {
            $naissance = new DateTime($date_naissance);
            return (int)$naissance->diff(new DateTime())->y;
        } catch (Exception $e) {
            return null;
        }
    }

    /** Âge lisible : « 3 ans », « 8 mois », ou « Âge inconnu ». */
    public static function ageLisible($date_naissance) {
        if (empty($date_naissance)) {
            return "Âge inconnu";
        }
        try {
            $diff = (new DateTime($date_naissance))->diff(new DateTime());
        } catch (Exception $e) {
            return "Âge inconnu";
        }
        if ($diff->y >= 1) {
            return $diff->y . ($diff->y > 1 ? ' ans' : ' an');
        }
        if ($diff->m >= 1) {
            return $diff->m . ' mois';
        }
        return 'Moins d\'un mois';
    }

    public static function libelleEspece($espece) {
        $libelles = ['chien' => 'Chien', 'chat' => 'Chat', 'nac' => 'NAC', 'autre' => 'Autre'];
        return $libelles[$espece] ?? ucfirst((string)$espece);
    }

    public static function libelleSexe($sexe) {
        return $sexe === 'feminin' ? 'Femelle' : ($sexe === 'masculin' ? 'Mâle' : (string)$sexe);
    }

    public static function libelleStatut($statut) {
        $libelles = ['disponible' => 'Disponible', 'reserve' => 'Réservé', 'adopte' => 'Adopté'];
        return $libelles[$statut] ?? (string)$statut;
    }

    /**
     * Enrichit une ligne de la base avec les données calculées utiles
     * à l'affichage et à l'API (âge, libellés, texte alternatif).
     */
    public static function presenter(array $a) {
        $a['age']          = self::calculerAge($a['date_naissance'] ?? null);
        $a['age_lisible']  = self::ageLisible($a['date_naissance'] ?? null);
        $a['espece_label'] = self::libelleEspece($a['espece'] ?? '');
        $a['sexe_label']   = self::libelleSexe($a['sexe'] ?? '');
        $a['statut_label'] = self::libelleStatut($a['statut'] ?? '');
        $a['vaccine']      = (bool)($a['vaccine'] ?? false);
        $a['sterilise']    = (bool)($a['sterilise'] ?? false);
        $a['identifie']    = (bool)($a['identifie'] ?? false);

        // Texte alternatif descriptif, pour les lecteurs d'écran
        $a['alt'] = sprintf(
            '%s, %s %s à l\'adoption',
            $a['nom'] ?? 'Animal',
            strtolower($a['espece_label']),
            strtolower($a['sexe_label'])
        );
        return $a;
    }

    /* ------------------------------------------------------------------
       Lecture
       ------------------------------------------------------------------ */

    /** Tous les animaux (back-office). */
    public function getAllAnimaux() {
        $stmt = $this->db->query("SELECT * FROM animal ORDER BY id_animal DESC");
        return array_map([self::class, 'presenter'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /** Un animal par son identifiant. */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM animal WHERE id_animal = ?");
        $stmt->execute([(int)$id]);
        $animal = $stmt->fetch(PDO::FETCH_ASSOC);
        return $animal ? self::presenter($animal) : null;
    }

    /**
     * Filtrage des animaux visibles par les visiteurs.
     * L'âge maximum est calculé à partir de la date de naissance :
     * un animal d'au plus N ans est né après (aujourd'hui - N - 1 an).
     *
     * @param array $criteres espece, sexe, age_max, vaccine, sterilise, identifie, statut
     */
    public function getFiltered(array $criteres = []) {
        $sql = "SELECT * FROM animal WHERE 1=1";
        $params = [];

        $espece = $criteres['espece'] ?? 'tous';
        if ($espece !== 'tous' && in_array($espece, self::ESPECES, true)) {
            $sql .= " AND espece = ?";
            $params[] = $espece;
        }

        $sexe = $criteres['sexe'] ?? 'tous';
        if ($sexe !== 'tous' && in_array($sexe, self::SEXES, true)) {
            $sql .= " AND sexe = ?";
            $params[] = $sexe;
        }

        if (isset($criteres['age_max']) && $criteres['age_max'] !== '' && $criteres['age_max'] !== null) {
            $age_max = (int)$criteres['age_max'];
            if ($age_max >= 0) {
                $sql .= " AND date_naissance IS NOT NULL AND date_naissance >= ?";
                $params[] = (new DateTime())->modify('-' . ($age_max + 1) . ' years')
                                            ->modify('+1 day')->format('Y-m-d');
            }
        }

        foreach (['vaccine', 'sterilise', 'identifie'] as $champ) {
            if (!empty($criteres[$champ])) {
                $sql .= " AND $champ = 1";
            }
        }

        $statut = $criteres['statut'] ?? null;
        if ($statut !== null && in_array($statut, self::STATUTS, true)) {
            $sql .= " AND statut = ?";
            $params[] = $statut;
        }

        // Les animaux disponibles remontent en premier
        $sql .= " ORDER BY FIELD(statut, 'disponible', 'reserve', 'adopte'), id_animal DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return array_map([self::class, 'presenter'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /* ------------------------------------------------------------------
       Écriture
       ------------------------------------------------------------------ */

    /** Vérifie un jeu de données avant écriture. Retourne la liste des erreurs. */
    public function valider(array $d) {
        $erreurs = [];

        if (trim($d['nom'] ?? '') === '') {
            $erreurs[] = "Le nom est obligatoire.";
        }
        if (!in_array($d['espece'] ?? '', self::ESPECES, true)) {
            $erreurs[] = "L'espèce est invalide.";
        }
        if (!in_array($d['sexe'] ?? '', self::SEXES, true)) {
            $erreurs[] = "Le sexe est invalide.";
        }
        if (isset($d['statut']) && !in_array($d['statut'], self::STATUTS, true)) {
            $erreurs[] = "Le statut est invalide.";
        }
        if (!empty($d['date_naissance'])) {
            $ts = strtotime($d['date_naissance']);
            if ($ts === false || $ts > time()) {
                $erreurs[] = "La date de naissance est invalide.";
            }
        }
        return $erreurs;
    }

    /** Crée un animal. Retourne son identifiant. */
    public function creer(array $d) {
        $sql = "INSERT INTO animal
                (nom, espece, sexe, date_naissance, description, vaccine, sterilise, identifie, photo, statut, date_arrivee)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            trim($d['nom']),
            $d['espece'],
            $d['sexe'],
            !empty($d['date_naissance']) ? $d['date_naissance'] : null,
            trim($d['description'] ?? ''),
            !empty($d['vaccine'])   ? 1 : 0,
            !empty($d['sterilise']) ? 1 : 0,
            !empty($d['identifie']) ? 1 : 0,
            $d['photo'],
            $d['statut'] ?? 'disponible',
            $d['date_arrivee'] ?? date('Y-m-d'),
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Met à jour un animal (tous les champs, hors photo). */
    public function mettreAJour($id, array $d) {
        $sql = "UPDATE animal
                SET nom = ?, espece = ?, sexe = ?, date_naissance = ?, description = ?,
                    vaccine = ?, sterilise = ?, identifie = ?, statut = ?
                WHERE id_animal = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($d['nom']),
            $d['espece'],
            $d['sexe'],
            !empty($d['date_naissance']) ? $d['date_naissance'] : null,
            trim($d['description'] ?? ''),
            !empty($d['vaccine'])   ? 1 : 0,
            !empty($d['sterilise']) ? 1 : 0,
            !empty($d['identifie']) ? 1 : 0,
            $d['statut'],
            (int)$id,
        ]);
    }

    /** Change uniquement le statut (utilisé par les réservations). */
    public function changerStatut($id, $statut) {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE animal SET statut = ? WHERE id_animal = ?");
        return $stmt->execute([$statut, (int)$id]);
    }

    /** Supprime un animal et retourne le nom de sa photo, pour nettoyage. */
    public function supprimer($id) {
        $animal = $this->getById($id);
        if (!$animal) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM animal WHERE id_animal = ?");
        $stmt->execute([(int)$id]);
        return $animal['photo'] ?? null;
    }

    /** Quelques compteurs pour le tableau de bord. */
    public function statistiques() {
        $stmt = $this->db->query(
            "SELECT statut, COUNT(*) AS total FROM animal GROUP BY statut"
        );
        $stats = ['disponible' => 0, 'reserve' => 0, 'adopte' => 0, 'total' => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            $stats[$ligne['statut']] = (int)$ligne['total'];
            $stats['total'] += (int)$ligne['total'];
        }
        return $stats;
    }
}
