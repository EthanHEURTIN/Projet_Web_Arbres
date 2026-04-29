import pandas as pd

CSV_FILE = 'Patrimoine_Arbore_Nettoye.csv'
SQL_FILE = 'data.sql'

print("Lecture du fichier CSV...")
df = pd.read_csv(CSV_FILE, sep=';', encoding='utf-8', low_memory=False)

# --- CORRECTION CRUCIALE ---
# Le CSV contient déjà une colonne 'id_arbre'. 
# Si on renomme 'OBJECTID' en 'id_arbre', on crée un doublon.
# On supprime donc l'ancienne colonne 'id_arbre' avant le renommage.
if 'id_arbre' in df.columns and 'OBJECTID' in df.columns:
    df = df.drop(columns=['id_arbre'])

# Renommage des colonnes
df = df.rename(columns={
    'OBJECTID': 'id_arbre',
    'fk_arb_etat': 'fk_etat',
    'fk_stadedev': 'fk_stade_dev',
    'fk_revetement': 'fk_revêtement'
})

print(f"{len(df)} lignes chargées. Génération du fichier SQL...")

# Liste des colonnes cibles pour le INSERT
cols_to_export = [
    'id_arbre', 'created_date', 'clc_quartier', 'clc_secteur',
    'haut_tot', 'haut_tronc', 'tronc_diam', 'fk_etat', 'fk_stade_dev',
    'fk_port', 'fk_pied', 'fk_situation', 'fk_revêtement',
    'commentaire_environnement', 'age_estim', 'fk_prec_estim',
    'clc_nbr_diag', 'dte_abattage', 'villeca', 'nom', 'feuillage',
    'remarquable', 'lon', 'lat'
]

with open(SQL_FILE, 'w', encoding='utf-8') as f:
    f.write("-- Insertion des données\nBEGIN;\n\n")
    
    for _, row in df.iterrows():
        values = []
        for col in cols_to_export:
            # On récupère la valeur. Comme on a supprimé le doublon, 
            # val sera maintenant une valeur unique (scalaire) et non une Series.
            val = row[col]
            
            if pd.isna(val):
                values.append('NULL')
            elif isinstance(val, (int, float)):
                values.append(str(val))
            else:
                # Pour le texte et les dates
                text = str(val).replace("'", "''")
                values.append(f"'{text}'")
        
        sql = f"INSERT INTO arbre ({', '.join(cols_to_export)}) VALUES ({', '.join(values)});\n"
        f.write(sql)
    
    f.write("\nCOMMIT;\n")

print(f"✅ Fichier {SQL_FILE} généré avec succès !")