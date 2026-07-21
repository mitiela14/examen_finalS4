DROP TABLE IF EXISTS historique_client;
DROP TABLE IF EXISTS solde_operateur;
DROP TABLE IF EXISTS commission_inter_operateur;
DROP TABLE IF EXISTS tranche_montant;
DROP TABLE IF EXISTS type_operation;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS prefixe;
DROP TABLE IF EXISTS admin;

DROP VIEW IF EXISTS vue_gains_par_type;
DROP VIEW IF EXISTS vue_gains_par_type_v2;

-- =====================================================================
-- TABLES
-- =====================================================================

CREATE TABLE prefixe (
    id_prefixe   INTEGER PRIMARY KEY AUTOINCREMENT,
    code         VARCHAR(3) NOT NULL UNIQUE,
    operateur    VARCHAR(20) NOT NULL DEFAULT 'telma',
    date_ajout   DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE utilisateur (
    id_utilisateur INTEGER PRIMARY KEY AUTOINCREMENT,
    nom           VARCHAR(50) NOT NULL DEFAULT '',
    statut        VARCHAR(20) NOT NULL DEFAULT 'actif',
    telephone      VARCHAR(15) NOT NULL UNIQUE,
    solde          DECIMAL(15,2) NOT NULL DEFAULT 0,
    date_creation  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
    id_admin  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom       VARCHAR(50) NOT NULL,
    code_acces VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE type_operation (
    id_type_operation INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle           VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE tranche_montant (
    id_tranche        INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min       DECIMAL(15,2) NOT NULL,
    montant_max       DECIMAL(15,2) NOT NULL,
    frais             DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id_type_operation)
);

CREATE TABLE historique_client (
    id_historique          INTEGER PRIMARY KEY AUTOINCREMENT,
    id_utilisateur         INTEGER NOT NULL,
    id_type_operation      INTEGER NOT NULL,
    montant                DECIMAL(15,2) NOT NULL,
    frais                  DECIMAL(15,2) NOT NULL DEFAULT 0,
    telephone_destinataire VARCHAR(15) DEFAULT NULL,
    solde_apres            DECIMAL(15,2) NOT NULL,
    frais_inclus           BOOLEAN DEFAULT 0,
    est_envoi_multiple     BOOLEAN DEFAULT 0,
    reference_envoi        VARCHAR(50) DEFAULT NULL,
    commission             DECIMAL(15,2) DEFAULT 0,
    operateur_destinataire VARCHAR(20) DEFAULT NULL,
    montant_recu           DECIMAL(15,2) DEFAULT 0,
    montant_debit          DECIMAL(15,2) DEFAULT 0,
    date_operation         DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id_type_operation)
);

CREATE TABLE commission_inter_operateur (
    id_commission INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur VARCHAR(20) NOT NULL,
    pourcentage_autres DECIMAL(5,2) NOT NULL
);


 CREATE TABLE promotion(
    id_promotion INTEGER PRIMARY KEY AUTOINCREMENT,
    pourcentage DECIMAL(5,2) NOT NULL,
    date_expiration DATETIME NOT NULL
);

CREATE TABLE solde_operateur (
    id_solde INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur VARCHAR(20) NOT NULL UNIQUE,
    montant_a_envoyer DECIMAL(15,2) NOT NULL DEFAULT 0
);

-- =====================================================================
-- VUES
-- =====================================================================

CREATE VIEW vue_gains_par_type AS
SELECT
    t.libelle AS type_operation,
    COUNT(h.id_historique) AS nombre_operations,
    SUM(h.frais) AS total_frais
FROM historique_client h
JOIN type_operation t ON t.id_type_operation = h.id_type_operation
GROUP BY t.libelle;

CREATE VIEW vue_gains_par_type_v2 AS
SELECT
    t.libelle AS type_operation,
    CASE 
        WHEN p.operateur = 'airtel' THEN 'notre_operateur'
        ELSE 'autres_operateurs'
    END AS categorie_operateur,
    COUNT(h.id_historique) AS nombre_operations,
    SUM(h.frais) AS total_frais
FROM historique_client h
JOIN type_operation t ON t.id_type_operation = h.id_type_operation
LEFT JOIN prefixe p ON SUBSTR(h.telephone_destinataire, 1, 3) = p.code
WHERE h.telephone_destinataire IS NOT NULL
GROUP BY t.libelle, 
    CASE 
        WHEN p.operateur = 'airtel' THEN 'notre_operateur'
        ELSE 'autres_operateurs'
    END;

-- =====================================================================
-- DONNEES DE TEST
-- =====================================================================

INSERT INTO prefixe (code, operateur) VALUES ('033', 'telma');
INSERT INTO prefixe (code, operateur) VALUES ('037', 'telma');
INSERT INTO prefixe (code, operateur) VALUES ('032', 'orange');
INSERT INTO prefixe (code, operateur) VALUES ('031', 'airtel');

INSERT INTO admin (nom, code_acces) VALUES ('Operateur Principal', 'admin123');

INSERT INTO type_operation (libelle) VALUES ('depot');
INSERT INTO type_operation (libelle) VALUES ('retrait');
INSERT INTO type_operation (libelle) VALUES ('transfert');

INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (1, 0, 999999999, 0);

INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (2, 0, 5000, 200);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (2, 5001, 20000, 500);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (2, 20001, 999999999, 1000);

INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (3, 0, 5000, 100);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (3, 5001, 20000, 300);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (3, 20001, 999999999, 700);

INSERT INTO commission_inter_operateur (operateur, pourcentage_autres) VALUES ('airtel', 1.00);

INSERT INTO solde_operateur (operateur, montant_a_envoyer) VALUES ('airtel', 0);
INSERT INTO solde_operateur (operateur, montant_a_envoyer) VALUES ('orange', 0);
INSERT INTO solde_operateur (operateur, montant_a_envoyer) VALUES ('telma', 0);

INSERT INTO utilisateur (nom, statut, telephone, solde) VALUES ('Narindra', 'actif', '0311234567', 500000);
INSERT INTO utilisateur (nom, statut, telephone, solde) VALUES ('NyAntema', 'actif', '0311112222', 5000);
