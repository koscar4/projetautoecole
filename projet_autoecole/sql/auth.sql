-- À exécuter dans phpMyAdmin sur la base castellane_auto
-- Onglet SQL → coller → Exécuter

CREATE TABLE IF NOT EXISTS admin (
    idadmin INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(50),
    prenom VARCHAR(50)
) ENGINE=InnoDB;

-- Compte par défaut : login=admin  /  mot de passe=admin123
INSERT IGNORE INTO admin (login, mot_de_passe, nom, prenom)
VALUES ('admin', 'admin123', 'Administrateur', 'Castellane');

-- Colonnes pour l'espace élève (ignorées si déjà présentes)
ALTER TABLE candidat
    ADD COLUMN IF NOT EXISTS login VARCHAR(100) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS mot_de_passe VARCHAR(255) DEFAULT NULL;
