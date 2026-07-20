DROP TABLE IF EXISTS prefixe;
CREATE TABLE prefixe (
    id_prefixe   INTEGER PRIMARY KEY AUTOINCREMENT,
    code         VARCHAR(3) NOT NULL UNIQUE,   -- ex: '033'
    date_ajout   DATETIME DEFAULT CURRENT_TIMESTAMP
);



DROP TABLE IF EXISTS utilisateur;
CREATE TABLE utilisateur (
    id_utilisateur INTEGER PRIMARY KEY AUTOINCREMENT,
    nom           VARCHAR(50) NOT NULL DEFAULT '',
    statut        VARCHAR(20) NOT NULL DEFAULT 'actif',  -- 'actif' | 'suspendu'
    telephone      VARCHAR(15) NOT NULL UNIQUE,  -- ex: '0331234567'
    solde          DECIMAL(15,2) NOT NULL DEFAULT 0,
    date_creation  DATETIME DEFAULT CURRENT_TIMESTAMP
);


DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
    id_admin  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom       VARCHAR(50) NOT NULL,
    code_acces VARCHAR(50) NOT NULL UNIQUE
);


DROP TABLE IF EXISTS type_operation;
CREATE TABLE type_operation (
    id_type_operation INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle           VARCHAR(20) NOT NULL UNIQUE  -- 'depot' | 'retrait' | 'transfert'
);


DROP TABLE IF EXISTS tranche_montant;
CREATE TABLE tranche_montant (
    id_tranche        INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min       DECIMAL(15,2) NOT NULL,
    montant_max       DECIMAL(15,2) NOT NULL,   -- utiliser une grande valeur pour "sans limite"
    frais             DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id_type_operation)
);


DROP TABLE IF EXISTS historique_client;
CREATE TABLE historique_client (
    id_historique         INTEGER PRIMARY KEY AUTOINCREMENT,
    id_utilisateur        INTEGER NOT NULL,
    id_type_operation     INTEGER NOT NULL,
    montant               DECIMAL(15,2) NOT NULL,
    frais                 DECIMAL(15,2) NOT NULL DEFAULT 0,
    telephone_destinataire VARCHAR(15) DEFAULT NULL,  -- rempli seulement si transfert
    solde_apres           DECIMAL(15,2) NOT NULL,
    date_operation         DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id_type_operation)
);


DROP VIEW IF EXISTS vue_gains_par_type;
CREATE VIEW vue_gains_par_type AS
SELECT
    t.libelle AS type_operation,
    COUNT(h.id_historique) AS nombre_operations,
    SUM(h.frais) AS total_frais
FROM historique_client h
JOIN type_operation t ON t.id_type_operation = h.id_type_operation
GROUP BY t.libelle;

-- =====================================================================
-- DONNEES DE TEST
-- =====================================================================

-- Prefixes autorises
INSERT INTO prefixe (code) VALUES ('033');
INSERT INTO prefixe (code) VALUES ('037');

-- Compte admin (code d'acces simple pour la V1)
INSERT INTO admin (nom, code_acces) VALUES ('Operateur Principal', 'admin123');

-- Types d'operation
INSERT INTO type_operation (libelle) VALUES ('depot');
INSERT INTO type_operation (libelle) VALUES ('retrait');
INSERT INTO type_operation (libelle) VALUES ('transfert');

-- Bareme de frais - RETRAIT (id_type_operation = 2)
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (2, 0, 5000, 200);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (2, 5001, 20000, 500);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (2, 20001, 999999999, 1000);

-- Bareme de frais - TRANSFERT (id_type_operation = 3)
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (3, 0, 5000, 100);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (3, 5001, 20000, 300);
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (3, 20001, 999999999, 700);

-- Le DEPOT n'a pas de frais dans cette V1 (pas de ligne tranche_montant necessaire,
-- ou on peut ajouter une tranche a 0 partout si l'equipe prefere garder la logique uniforme)
INSERT INTO tranche_montant (id_type_operation, montant_min, montant_max, frais) VALUES (1, 0, 999999999, 0);

-- Quelques clients de test

INSERT INTO utilisateur (nom, statut, telephone, solde) VALUES ('Narindra', 'actif', '0331234567', 15000);
INSERT INTO utilisateur (nom, statut, telephone, solde) VALUES ('NyAntema', 'actif', '0371112222', 5000);


-- =====================================================================
-- VERSION 2 - MODIFICATIONS (Tag v2)
-- =====================================================================

-- 1. Ajout colonne operateur dans prefixe
ALTER TABLE prefixe ADD COLUMN operateur VARCHAR(20) DEFAULT 'telma';

-- 2. Table commission_inter_operateur (% en plus pour autres operateurs)
CREATE TABLE IF NOT EXISTS commission_inter_operateur (
    id_commission INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur VARCHAR(20) NOT NULL,
    pourcentage_autres DECIMAL(5,2) NOT NULL
);

-- 3. Table solde_operateur (montants a envoyer a chaque operateur)
CREATE TABLE IF NOT EXISTS solde_operateur (
    id_solde INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur VARCHAR(20) NOT NULL UNIQUE,
    montant_a_envoyer DECIMAL(15,2) NOT NULL DEFAULT 0
);

-- 4. Ajout colonnes dans historique_client
ALTER TABLE historique_client ADD COLUMN frais_inclus BOOLEAN DEFAULT 0;
ALTER TABLE historique_client ADD COLUMN est_envoi_multiple BOOLEAN DEFAULT 0;
ALTER TABLE historique_client ADD COLUMN reference_envoi VARCHAR(50) DEFAULT NULL;

-- 5. Vue v2 pour separer gains operateur et autres operateurs
DROP VIEW IF EXISTS vue_gains_par_type_v2;
CREATE VIEW vue_gains_par_type_v2 AS
SELECT
    t.libelle AS type_operation,
    CASE 
        WHEN p.operateur = 'telma' THEN 'operateur'
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
        WHEN p.operateur = 'telma' THEN 'operateur'
        ELSE 'autres_operateurs'
    END;

-- 6. Donnees de test V2
UPDATE prefixe SET operateur = 'telma' WHERE code = '033';
UPDATE prefixe SET operateur = 'telma' WHERE code = '037';
INSERT INTO prefixe (code, operateur) VALUES ('032', 'orange');
INSERT INTO prefixe (code, operateur) VALUES ('031', 'airtel');

INSERT INTO commission_inter_operateur (operateur, pourcentage_autres) VALUES ('telma', 5.00);

INSERT INTO solde_operateur (operateur, montant_a_envoyer) VALUES ('telma', 0);
INSERT INTO solde_operateur (operateur, montant_a_envoyer) VALUES ('orange', 0);
INSERT INTO solde_operateur (operateur, montant_a_envoyer) VALUES ('airtel', 0);


