CREATE TABLE IF NOT EXISTS arbre (
    id_arbre INTEGER PRIMARY KEY,
    created_date DATE,
    
    fk_etat TEXT NOT NULL CHECK (fk_etat IN ('ABATTU', 'EN PLACE', 'Essouché', 'Non essouché', 'REMPLACÉ', 'SUPPRIMÉ')), 
    fk_stade_dev TEXT NOT NULL CHECK (fk_stade_dev IN ('jeune', 'adulte', 'vieux', 'senescent')),
    fk_port VARCHAR(50),
    fk_pied VARCHAR(50),
    fk_situation TEXT CHECK (fk_situation IN ('Alignement', 'Groupe', 'Isolé')),
    
    fk_revêtement INTEGER NOT NULL DEFAULT 0,
    commentaire_environnement TEXT,
    age_estim INTEGER,
    fk_prec_estim INTEGER,
    clc_nbr_diag INTEGER,
    dte_abattage DATE,
    
    villeca TEXT CHECK (villeca IN ('VILLE', 'CASQ')),
    nom VARCHAR(50),
    feuillage TEXT CHECK (feuillage IN ('Conifère', 'Feuillu')),
    
    remarquable INTEGER NOT NULL DEFAULT 0,
    
    lon REAL NOT NULL,
    lat REAL NOT NULL,
    haut_tot REAL NOT NULL,
    haut_tronc REAL NOT NULL,
    tronc_diam REAL NOT NULL,
    
    clc_quartier TEXT NOT NULL,
    clc_secteur TEXT NOT NULL
);