<?php
/**
 * MODELE - Castellane Auto
 * Accès à la base de données (PDO) - Conforme CDC PPE2
 */
class ModeleAutoEcole {

    private $unPdo;

    public function __construct() {
        try {
            $this->unPdo = new PDO(
                'mysql:host=localhost;
                dbname=castellane_auto;
                charset=utf8mb4',
                'root', 'root',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            die('Connexion échouée : ' . $e->getMessage());
        }
    }

    /* ============================================================
       CANDIDATS
    ============================================================ */

    public function selectAll_candidats() {
        $req = "SELECT c.*, e.nom_etablissement
                FROM candidat c
                LEFT JOIN etablissement e ON c.idetablissement = e.idetablissement
                ORDER BY c.nom, c.prenom";
        $exec = $this->unPdo->prepare($req);
        $exec->execute();
        return $exec->fetchAll();
    }

    public function selectWhere_candidat($id) {
        $req = "SELECT * FROM candidat WHERE idcandidat = :id";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([':id' => $id]);
        return $exec->fetch();
    }

    public function insert_candidat($tab) {
        $req = "INSERT INTO candidat (nom, prenom, adresse, tel, email, date_naissance,
                    date_prevue_code, date_prevue_permis, idetablissement)
                VALUES (:nom, :prenom, :adresse, :tel, :email, :ddn,
                    :date_code, :date_permis, :idetab)";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':nom'         => $tab['nom'],
            ':prenom'      => $tab['prenom'],
            ':adresse'     => $tab['adresse'] ?? '',
            ':tel'         => $tab['tel'] ?? '',
            ':email'       => $tab['email'] ?? '',
            ':ddn'         => $tab['date_naissance'] ?: null,
            ':date_code'   => $tab['date_prevue_code'] ?: null,
            ':date_permis' => $tab['date_prevue_permis'] ?: null,
            ':idetab'      => $tab['idetablissement'] ?: null,
        ]);
    }

    public function update_candidat($tab) {
        $req = "UPDATE candidat SET nom=:nom, prenom=:prenom, adresse=:adresse,
                    tel=:tel, email=:email, date_naissance=:ddn,
                    date_prevue_code=:date_code, date_prevue_permis=:date_permis,
                    idetablissement=:idetab
                WHERE idcandidat=:id";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':id'          => $tab['idcandidat'],
            ':nom'         => $tab['nom'],
            ':prenom'      => $tab['prenom'],
            ':adresse'     => $tab['adresse'] ?? '',
            ':tel'         => $tab['tel'] ?? '',
            ':email'       => $tab['email'] ?? '',
            ':ddn'         => $tab['date_naissance'] ?: null,
            ':date_code'   => $tab['date_prevue_code'] ?: null,
            ':date_permis' => $tab['date_prevue_permis'] ?: null,
            ':idetab'      => $tab['idetablissement'] ?: null,
        ]);
    }

    public function delete_candidat($id) {
        $exec = $this->unPdo->prepare("DELETE FROM candidat WHERE idcandidat=:id");
        $exec->execute([':id' => $id]);
    }

    /* ============================================================
       ÉTABLISSEMENTS
    ============================================================ */

    public function selectAll_etablissements() {
        $exec = $this->unPdo->prepare("SELECT * FROM etablissement ORDER BY nom_etablissement");
        $exec->execute();
        return $exec->fetchAll();
    }

    /* ============================================================
       MONITEURS
    ============================================================ */

    public function selectAll_moniteurs() {
        $exec = $this->unPdo->prepare("SELECT * FROM moniteur ORDER BY nom, prenom");
        $exec->execute();
        return $exec->fetchAll();
    }

    public function selectWhere_moniteur($id) {
        $exec = $this->unPdo->prepare("SELECT * FROM moniteur WHERE idmoniteur=:id");
        $exec->execute([':id' => $id]);
        return $exec->fetch();
    }

    public function insert_moniteur($tab) {
        $req = "INSERT INTO moniteur (nom, prenom, telephone, email, permis)
                VALUES (:nom, :prenom, :telephone, :email, :permis)";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':nom'       => $tab['nom'],
            ':prenom'    => $tab['prenom'],
            ':telephone' => $tab['telephone'],
            ':email'     => $tab['email'],
            ':permis'    => $tab['permis'],
        ]);
    }

    public function update_moniteur($tab) {
        $req = "UPDATE moniteur SET nom=:nom, prenom=:prenom, telephone=:telephone,
                    email=:email, permis=:permis
                WHERE idmoniteur=:id";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':id'        => $tab['idmoniteur'],
            ':nom'       => $tab['nom'],
            ':prenom'    => $tab['prenom'],
            ':telephone' => $tab['telephone'],
            ':email'     => $tab['email'],
            ':permis'    => $tab['permis'],
        ]);
    }

    public function delete_moniteur($id) {
        $exec = $this->unPdo->prepare("DELETE FROM moniteur WHERE idmoniteur=:id");
        $exec->execute([':id' => $id]);
    }

    /* ============================================================
       MODÈLES VOITURE
    ============================================================ */

    public function selectAll_modeles() {
        $exec = $this->unPdo->prepare("SELECT * FROM modele_voiture ORDER BY marque, modele");
        $exec->execute();
        return $exec->fetchAll();
    }

    /* ============================================================
       VOITURES
    ============================================================ */

    public function selectAll_voitures() {
        $req = "SELECT v.*, mv.marque, mv.modele AS nom_modele, mv.type_boite
                FROM voiture v JOIN modele_voiture mv ON v.idmodele = mv.idmodele
                ORDER BY v.immatriculation";
        $exec = $this->unPdo->prepare($req);
        $exec->execute();
        return $exec->fetchAll();
    }

    public function selectWhere_voiture($id) {
        $exec = $this->unPdo->prepare("SELECT * FROM voiture WHERE idvoiture=:id");
        $exec->execute([':id' => $id]);
        return $exec->fetch();
    }

    public function insert_voiture($tab) {
        $req = "INSERT INTO voiture (immatriculation, annee, kilometrage_total, idmodele)
                VALUES (:immat, :annee, :km, :idmodele)";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':immat'   => $tab['immatriculation'],
            ':annee'   => $tab['annee'],
            ':km'      => $tab['kilometrage_total'],
            ':idmodele'=> $tab['idmodele'],
        ]);
    }

    public function update_voiture($tab) {
        $req = "UPDATE voiture SET immatriculation=:immat, annee=:annee,
                    kilometrage_total=:km, idmodele=:idmodele
                WHERE idvoiture=:id";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':id'      => $tab['idvoiture'],
            ':immat'   => $tab['immatriculation'],
            ':annee'   => $tab['annee'],
            ':km'      => $tab['kilometrage_total'],
            ':idmodele'=> $tab['idmodele'],
        ]);
    }

    public function delete_voiture($id) {
        $exec = $this->unPdo->prepare("DELETE FROM voiture WHERE idvoiture=:id");
        $exec->execute([':id' => $id]);
    }

    /* ============================================================
       LEÇONS
    ============================================================ */

    public function selectAll_lecons() {
        $req = "SELECT l.*,
                    CONCAT(c.nom,' ',c.prenom) AS candidat_nom,
                    CONCAT(m.nom,' ',m.prenom) AS moniteur_nom,
                    CONCAT(mv.marque,' ',mv.modele) AS vehicule_nom,
                    v.immatriculation
                FROM lecon l
                JOIN candidat c ON l.idcandidat = c.idcandidat
                JOIN moniteur m ON l.idmoniteur = m.idmoniteur
                JOIN voiture v ON l.idvoiture = v.idvoiture
                JOIN modele_voiture mv ON v.idmodele = mv.idmodele
                ORDER BY l.date_lecon DESC, l.heure_debut";
        $exec = $this->unPdo->prepare($req);
        $exec->execute();
        return $exec->fetchAll();
    }

    public function selectWhere_lecon($id) {
        $exec = $this->unPdo->prepare("SELECT * FROM lecon WHERE idlecon=:id");
        $exec->execute([':id' => $id]);
        return $exec->fetch();
    }

    public function insert_lecon($tab) {
        $req = "INSERT INTO lecon (date_lecon, heure_debut, heure_fin, idcandidat, idmoniteur, idvoiture)
                VALUES (:date, :debut, :fin, :idcand, :idmon, :idvoit)";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':date'   => $tab['date_lecon'],
            ':debut'  => $tab['heure_debut'],
            ':fin'    => $tab['heure_fin'],
            ':idcand' => $tab['idcandidat'],
            ':idmon'  => $tab['idmoniteur'],
            ':idvoit' => $tab['idvoiture'],
        ]);
    }

    public function update_lecon($tab) {
        $req = "UPDATE lecon SET date_lecon=:date, heure_debut=:debut, heure_fin=:fin,
                    idcandidat=:idcand, idmoniteur=:idmon, idvoiture=:idvoit
                WHERE idlecon=:id";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':id'     => $tab['idlecon'],
            ':date'   => $tab['date_lecon'],
            ':debut'  => $tab['heure_debut'],
            ':fin'    => $tab['heure_fin'],
            ':idcand' => $tab['idcandidat'],
            ':idmon'  => $tab['idmoniteur'],
            ':idvoit' => $tab['idvoiture'],
        ]);
    }

    public function delete_lecon($id) {
        $exec = $this->unPdo->prepare("DELETE FROM lecon WHERE idlecon=:id");
        $exec->execute([':id' => $id]);
    }

    /* ============================================================
       PLANNINGS (états périodiques)
    ============================================================ */

    public function planning_moniteur($idmoniteur, $date_debut, $date_fin) {
        $req = "SELECT l.date_lecon, l.heure_debut, l.heure_fin, l.duree_heures,
                    CONCAT(c.nom,' ',c.prenom) AS candidat,
                    CONCAT(mv.marque,' ',mv.modele) AS vehicule,
                    v.immatriculation
                FROM lecon l
                JOIN candidat c ON l.idcandidat = c.idcandidat
                JOIN voiture v ON l.idvoiture = v.idvoiture
                JOIN modele_voiture mv ON v.idmodele = mv.idmodele
                WHERE l.idmoniteur = :idmon
                  AND l.date_lecon BETWEEN :debut AND :fin
                ORDER BY l.date_lecon, l.heure_debut";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([':idmon' => $idmoniteur, ':debut' => $date_debut, ':fin' => $date_fin]);
        return $exec->fetchAll();
    }

    public function suivi_km_mensuel($annee, $mois) {
        $req = "SELECT sk.*, CONCAT(mv.marque,' ',mv.modele) AS vehicule_nom, v.immatriculation
                FROM suivi_km sk
                JOIN voiture v ON sk.idvoiture = v.idvoiture
                JOIN modele_voiture mv ON v.idmodele = mv.idmodele
                WHERE sk.annee = :annee AND sk.mois = :mois
                ORDER BY sk.km_parcourus DESC";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([':annee' => $annee, ':mois' => $mois]);
        return $exec->fetchAll();
    }

    /* ============================================================
       FACTURATION
    ============================================================ */

    public function selectAll_facturations() {
        $req = "SELECT f.*, CONCAT(c.nom,' ',c.prenom) AS candidat_nom
                FROM facturation f
                JOIN candidat c ON f.idcandidat = c.idcandidat
                ORDER BY f.date_facture DESC";
        $exec = $this->unPdo->prepare($req);
        $exec->execute();
        return $exec->fetchAll();
    }

    public function selectWhere_facturation($id) {
        $exec = $this->unPdo->prepare("SELECT * FROM facturation WHERE idfacturation=:id");
        $exec->execute([':id' => $id]);
        return $exec->fetch();
    }

    public function insert_facturation($tab) {
        $montant = $this->calculMontant($tab);
        $req = "INSERT INTO facturation (idcandidat, date_facture, mode_facturation,
                    nb_heures, tarif_horaire, montant_forfait, nb_heures_incluses, montant_total, statut)
                VALUES (:idcand, :date, :mode, :nbh, :tarif, :forfait, :nbhinc, :total, :statut)";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':idcand'  => $tab['idcandidat'],
            ':date'    => $tab['date_facture'],
            ':mode'    => $tab['mode_facturation'],
            ':nbh'     => $tab['nb_heures'] ?: null,
            ':tarif'   => $tab['tarif_horaire'] ?: null,
            ':forfait' => $tab['montant_forfait'] ?: null,
            ':nbhinc'  => $tab['nb_heures_incluses'] ?: null,
            ':total'   => $montant,
            ':statut'  => $tab['statut'] ?? 'en_attente',
        ]);
    }

    public function update_facturation($tab) {
        $montant = $this->calculMontant($tab);
        $req = "UPDATE facturation SET idcandidat=:idcand, date_facture=:date,
                    mode_facturation=:mode, nb_heures=:nbh, tarif_horaire=:tarif,
                    montant_forfait=:forfait, nb_heures_incluses=:nbhinc,
                    montant_total=:total, statut=:statut
                WHERE idfacturation=:id";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':id'      => $tab['idfacturation'],
            ':idcand'  => $tab['idcandidat'],
            ':date'    => $tab['date_facture'],
            ':mode'    => $tab['mode_facturation'],
            ':nbh'     => $tab['nb_heures'] ?: null,
            ':tarif'   => $tab['tarif_horaire'] ?: null,
            ':forfait' => $tab['montant_forfait'] ?: null,
            ':nbhinc'  => $tab['nb_heures_incluses'] ?: null,
            ':total'   => $montant,
            ':statut'  => $tab['statut'],
        ]);
    }

    public function delete_facturation($id) {
        $exec = $this->unPdo->prepare("DELETE FROM facturation WHERE idfacturation=:id");
        $exec->execute([':id' => $id]);
    }

    private function calculMontant($tab) {
        if ($tab['mode_facturation'] === 'heure') {
            return ($tab['nb_heures'] ?? 0) * ($tab['tarif_horaire'] ?? 0);
        }
        return $tab['montant_forfait'] ?? 0;
    }

    /* ============================================================
       AUTH ADMIN
    ============================================================ */
    public function getAdmin($login) {
        $exec = $this->unPdo->prepare("SELECT * FROM admin WHERE login = :login");
        $exec->execute([':login' => $login]);
        return $exec->fetch();
    }

    /* ============================================================
       AUTH ÉLÈVE (espace front)
    ============================================================ */
    public function getEleveByLogin($email) {
        $exec = $this->unPdo->prepare(
            "SELECT * FROM candidat WHERE login = :login AND mot_de_passe IS NOT NULL"
        );
        $exec->execute([':login' => $email]);
        return $exec->fetch();
    }

    public function loginEleve_existe($email) {
        $exec = $this->unPdo->prepare("SELECT COUNT(*) FROM candidat WHERE login = :login");
        $exec->execute([':login' => $email]);
        return $exec->fetchColumn() > 0;
    }

    public function inscrireEleve($tab) {
        $req = "INSERT INTO candidat (nom, prenom, email, tel, adresse, date_naissance, login, mot_de_passe)
                VALUES (:nom, :prenom, :email, :tel, :adresse, :ddn, :login, :mdp)";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([
            ':nom'    => $tab['nom'],
            ':prenom' => $tab['prenom'],
            ':email'  => $tab['email'],
            ':tel'    => $tab['tel'] ?? '',
            ':adresse'=> $tab['adresse'] ?? '',
            ':ddn'    => $tab['date_naissance'] ?: null,
            ':login'  => $tab['email'],
            ':mdp'    => $tab['mot_de_passe'],
        ]);
    }

    public function lecons_eleve($idcandidat) {
        $req = "SELECT l.*, CONCAT(m.nom,' ',m.prenom) AS moniteur_nom,
                    CONCAT(mv.marque,' ',mv.modele) AS vehicule_nom
                FROM lecon l
                JOIN moniteur m ON l.idmoniteur = m.idmoniteur
                JOIN voiture v ON l.idvoiture = v.idvoiture
                JOIN modele_voiture mv ON v.idmodele = mv.idmodele
                WHERE l.idcandidat = :id
                ORDER BY l.date_lecon DESC, l.heure_debut";
        $exec = $this->unPdo->prepare($req);
        $exec->execute([':id' => $idcandidat]);
        return $exec->fetchAll();
    }

    public function factures_eleve($idcandidat) {
        $exec = $this->unPdo->prepare(
            "SELECT * FROM facturation WHERE idcandidat = :id ORDER BY date_facture DESC"
        );
        $exec->execute([':id' => $idcandidat]);
        return $exec->fetchAll();
    }
}
?>
