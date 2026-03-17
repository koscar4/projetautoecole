-- ============================================================
-- BASE DE DONNÉES : CASTELLANE AUTO - Conforme au CDC PPE2
-- ============================================================

CREATE DATABASE IF NOT EXISTS castellane_auto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE castellane_auto;

-- ============================================================
-- ÉTABLISSEMENT (RG2 : pour candidats étudiants)
-- ============================================================
CREATE TABLE IF NOT EXISTS etablissement (
    idetablissement INT AUTO_INCREMENT PRIMARY KEY,
    nom_etablissement VARCHAR(100) NOT NULL,
    adresse_etablissement VARCHAR(200) NOT NULL
) ENGINE=InnoDB;

-- ============================================================
-- CANDIDAT (CLIENT)
-- ============================================================
CREATE TABLE IF NOT EXISTS candidat (
    idcandidat INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    adresse VARCHAR(200),
    tel VARCHAR(20),
    email VARCHAR(100),
    date_naissance DATE,
    -- RG1 : dates prévues code et permis
    date_prevue_code DATE,
    date_prevue_permis DATE,
    -- RG2 : étudiant lié à un établissement (nullable)
    idetablissement INT DEFAULT NULL,
    FOREIGN KEY (idetablissement) REFERENCES etablissement(idetablissement) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- MONITEUR
-- ============================================================
CREATE TABLE IF NOT EXISTS moniteur (
    idmoniteur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(100),
    permis VARCHAR(10) NOT NULL COMMENT 'Type de permis enseigné : B, A, C...'
) ENGINE=InnoDB;

-- ============================================================
-- MODELE_VOITURE
-- ============================================================
CREATE TABLE IF NOT EXISTS modele_voiture (
    idmodele INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    type_boite ENUM('manuelle','automatique') DEFAULT 'manuelle'
) ENGINE=InnoDB;

-- ============================================================
-- VOITURE (VÉHICULE)
-- ============================================================
CREATE TABLE IF NOT EXISTS voiture (
    idvoiture INT AUTO_INCREMENT PRIMARY KEY,
    immatriculation VARCHAR(20) NOT NULL UNIQUE,
    annee INT,
    kilometrage_total INT DEFAULT 0,
    idmodele INT NOT NULL,
    FOREIGN KEY (idmodele) REFERENCES modele_voiture(idmodele)
) ENGINE=InnoDB;

-- ============================================================
-- LECON
-- Contrainte non-chevauchement gérée par TRIGGER
-- ============================================================
CREATE TABLE IF NOT EXISTS lecon (
    idlecon INT AUTO_INCREMENT PRIMARY KEY,
    date_lecon DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    duree_heures DECIMAL(4,2) GENERATED ALWAYS AS (TIME_TO_SEC(TIMEDIFF(heure_fin, heure_debut))/3600) STORED,
    idcandidat INT NOT NULL,
    idmoniteur INT NOT NULL,
    idvoiture INT NOT NULL,
    FOREIGN KEY (idcandidat) REFERENCES candidat(idcandidat),
    FOREIGN KEY (idmoniteur) REFERENCES moniteur(idmoniteur),
    FOREIGN KEY (idvoiture) REFERENCES voiture(idvoiture),
    -- Contrainte métier : heure_fin > heure_debut
    CONSTRAINT chk_heures CHECK (heure_fin > heure_debut)
) ENGINE=InnoDB;

-- ============================================================
-- TRIGGER : Non-chevauchement des leçons (RG CDC §6)
-- ============================================================
DELIMITER $$

CREATE TRIGGER trg_lecon_no_overlap_insert
BEFORE INSERT ON lecon
FOR EACH ROW
BEGIN
    -- Vérif chevauchement candidat
    IF EXISTS (
        SELECT 1 FROM lecon
        WHERE idcandidat = NEW.idcandidat
          AND date_lecon = NEW.date_lecon
          AND heure_debut < NEW.heure_fin
          AND heure_fin > NEW.heure_debut
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chevauchement de leçon pour ce candidat.';
    END IF;
    -- Vérif chevauchement moniteur
    IF EXISTS (
        SELECT 1 FROM lecon
        WHERE idmoniteur = NEW.idmoniteur
          AND date_lecon = NEW.date_lecon
          AND heure_debut < NEW.heure_fin
          AND heure_fin > NEW.heure_debut
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chevauchement de leçon pour ce moniteur.';
    END IF;
    -- Vérif chevauchement voiture
    IF EXISTS (
        SELECT 1 FROM lecon
        WHERE idvoiture = NEW.idvoiture
          AND date_lecon = NEW.date_lecon
          AND heure_debut < NEW.heure_fin
          AND heure_fin > NEW.heure_debut
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chevauchement de leçon pour ce véhicule.';
    END IF;
END$$

CREATE TRIGGER trg_lecon_no_overlap_update
BEFORE UPDATE ON lecon
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM lecon
        WHERE idcandidat = NEW.idcandidat
          AND date_lecon = NEW.date_lecon
          AND heure_debut < NEW.heure_fin
          AND heure_fin > NEW.heure_debut
          AND idlecon != NEW.idlecon
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chevauchement de leçon pour ce candidat.';
    END IF;
    IF EXISTS (
        SELECT 1 FROM lecon
        WHERE idmoniteur = NEW.idmoniteur
          AND date_lecon = NEW.date_lecon
          AND heure_debut < NEW.heure_fin
          AND heure_fin > NEW.heure_debut
          AND idlecon != NEW.idlecon
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chevauchement de leçon pour ce moniteur.';
    END IF;
    IF EXISTS (
        SELECT 1 FROM lecon
        WHERE idvoiture = NEW.idvoiture
          AND date_lecon = NEW.date_lecon
          AND heure_debut < NEW.heure_fin
          AND heure_fin > NEW.heure_debut
          AND idlecon != NEW.idlecon
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chevauchement de leçon pour ce véhicule.';
    END IF;
END$$

DELIMITER ;

-- ============================================================
-- FACTURATION
-- Modes : heure, forfait_pack, forfait_global
-- ============================================================
CREATE TABLE IF NOT EXISTS facturation (
    idfacturation INT AUTO_INCREMENT PRIMARY KEY,
    idcandidat INT NOT NULL,
    date_facture DATE NOT NULL,
    mode_facturation ENUM('heure','forfait_pack','forfait_global') NOT NULL,
    -- Pour mode heure
    nb_heures DECIMAL(5,2) DEFAULT NULL,
    tarif_horaire DECIMAL(8,2) DEFAULT NULL,
    -- Pour modes forfait
    montant_forfait DECIMAL(8,2) DEFAULT NULL,
    nb_heures_incluses INT DEFAULT NULL,
    -- Montant total calculé
    montant_total DECIMAL(8,2) NOT NULL,
    statut ENUM('en_attente','payee','annulee') DEFAULT 'en_attente',
    FOREIGN KEY (idcandidat) REFERENCES candidat(idcandidat)
) ENGINE=InnoDB;

-- ============================================================
-- SUIVI KM MENSUEL (état périodique)
-- ============================================================
CREATE TABLE IF NOT EXISTS suivi_km (
    idsuivi INT AUTO_INCREMENT PRIMARY KEY,
    idvoiture INT NOT NULL,
    annee INT NOT NULL,
    mois INT NOT NULL,
    km_debut INT NOT NULL,
    km_fin INT NOT NULL,
    km_parcourus INT GENERATED ALWAYS AS (km_fin - km_debut) STORED,
    FOREIGN KEY (idvoiture) REFERENCES voiture(idvoiture),
    UNIQUE KEY uk_voiture_mois (idvoiture, annee, mois)
) ENGINE=InnoDB;

-- ============================================================
-- JEU D'ESSAI RÉALISTE
-- ============================================================

INSERT INTO etablissement (nom_etablissement, adresse_etablissement) VALUES
('Lycée Bonaparte', '123 Avenue de la République, 83000 Toulon'),
('IUT de Toulon', '10 Rue du Docteur Babut, 83000 Toulon'),
('Université de Toulon', '850 Route de La Salle, 83130 La Garde');

INSERT INTO moniteur (nom, prenom, telephone, email, permis) VALUES
('Dupont', 'Jean', '0494123456', 'j.dupont@castellane.fr', 'B'),
('Martin', 'Sophie', '0494234567', 's.martin@castellane.fr', 'B'),
('Bernard', 'Luc', '0494345678', 'l.bernard@castellane.fr', 'A');

INSERT INTO modele_voiture (marque, modele, type_boite) VALUES
('Renault', 'Clio V', 'manuelle'),
('Peugeot', '208', 'manuelle'),
('Toyota', 'Yaris', 'automatique');

INSERT INTO voiture (immatriculation, annee, kilometrage_total, idmodele) VALUES
('AB-123-CD', 2022, 45000, 1),
('EF-456-GH', 2021, 62000, 2),
('IJ-789-KL', 2023, 18000, 3);

INSERT INTO candidat (nom, prenom, adresse, tel, email, date_naissance, date_prevue_code, date_prevue_permis, idetablissement) VALUES
('Moreau', 'Alice', '5 Rue Gambetta, 83100 Toulon', '0612345678', 'alice.moreau@email.fr', '2005-03-15', '2024-06-01', '2025-01-15', 1),
('Petit', 'Thomas', '12 Bd de Strasbourg, 83000 Toulon', '0623456789', 'thomas.petit@email.fr', '2003-07-22', '2024-04-15', '2024-10-01', NULL),
('Garcia', 'Emma', '8 Allée des Pins, 83200 Toulon', '0634567890', 'emma.garcia@email.fr', '2004-11-08', '2024-09-01', '2025-03-01', 2),
('Robert', 'Lucas', '3 Chemin de Lorgues, 83100 Toulon', '0645678901', 'lucas.robert@email.fr', '2002-01-30', NULL, NULL, NULL),
('Simon', 'Léa', '20 Rue Victor Hugo, 83000 Toulon', '0656789012', 'lea.simon@email.fr', '2005-09-12', '2025-02-01', '2025-08-01', 3);

INSERT INTO lecon (date_lecon, heure_debut, heure_fin, idcandidat, idmoniteur, idvoiture) VALUES
('2025-01-10', '09:00:00', '11:00:00', 1, 1, 1),
('2025-01-10', '14:00:00', '16:00:00', 2, 2, 2),
('2025-01-13', '10:00:00', '12:00:00', 1, 1, 1),
('2025-01-13', '13:00:00', '15:00:00', 3, 2, 2),
('2025-01-15', '08:00:00', '10:00:00', 4, 3, 3),
('2025-01-20', '15:00:00', '17:00:00', 5, 1, 1);

INSERT INTO facturation (idcandidat, date_facture, mode_facturation, nb_heures, tarif_horaire, montant_total, statut) VALUES
(1, '2025-01-31', 'heure', 4, 35.00, 140.00, 'payee'),
(2, '2025-01-31', 'forfait_pack', NULL, NULL, 320.00, 'payee'),
(3, '2025-01-31', 'forfait_global', NULL, NULL, 1200.00, 'en_attente');

INSERT INTO suivi_km (idvoiture, annee, mois, km_debut, km_fin) VALUES
(1, 2025, 1, 45000, 45800),
(2, 2025, 1, 62000, 62500),
(3, 2025, 1, 18000, 18200);

-- ============================================================
-- REQUÊTES SQL DEMANDÉES PAR LE CDC
-- ============================================================

-- R1 : Planning journalier d'un moniteur
-- SELECT l.date_lecon, l.heure_debut, l.heure_fin,
--        CONCAT(c.nom,' ',c.prenom) AS candidat,
--        CONCAT(mv.marque,' ',mv.modele) AS vehicule
-- FROM lecon l
-- JOIN candidat c ON l.idcandidat = c.idcandidat
-- JOIN voiture v ON l.idvoiture = v.idvoiture
-- JOIN modele_voiture mv ON v.idmodele = mv.idmodele
-- WHERE l.idmoniteur = 1 AND l.date_lecon = '2025-01-10'
-- ORDER BY l.heure_debut;

-- R2 : Suivi km mensuel par véhicule
-- SELECT CONCAT(mv.marque,' ',mv.modele) AS vehicule, v.immatriculation,
--        sk.annee, sk.mois, sk.km_parcourus
-- FROM suivi_km sk
-- JOIN voiture v ON sk.idvoiture = v.idvoiture
-- JOIN modele_voiture mv ON v.idmodele = mv.idmodele
-- WHERE sk.annee = 2025 AND sk.mois = 1
-- ORDER BY sk.km_parcourus DESC;

-- R3 : Synthèse facturation par candidat
-- SELECT CONCAT(c.nom,' ',c.prenom) AS candidat,
--        f.mode_facturation, f.montant_total, f.statut
-- FROM facturation f
-- JOIN candidat c ON f.idcandidat = c.idcandidat
-- ORDER BY f.date_facture DESC;

-- R4 : Candidats avec leur date code/permis (RG1)
-- SELECT nom, prenom, date_prevue_code, date_prevue_permis
-- FROM candidat
-- ORDER BY date_prevue_code;

-- R5 : Candidats étudiants avec leur établissement (RG2)
-- SELECT c.nom, c.prenom, e.nom_etablissement, e.adresse_etablissement
-- FROM candidat c
-- JOIN etablissement e ON c.idetablissement = e.idetablissement;
