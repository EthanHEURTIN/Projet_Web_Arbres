import joblib
import pandas as pd
import argparse
import os
import sys

os.chdir(os.path.dirname(os.path.abspath(__file__)))

# CONFIGURATION DES ARGUMENTS
parser = argparse.ArgumentParser(description="Prédiction de l'âge d'un arbre")
parser.add_argument('--tronc_diam', type=float, required=True, help="Diamètre du tronc (en mètres)")
parser.add_argument('--haut_tot', type=float, required=True, help="Hauteur totale de l'arbre (en mètres)")
parser.add_argument('--haut_tronc', type=float, required=True, help="Hauteur du tronc (en mètres)")
parser.add_argument('--fk_stadedev', type=str, required=True, help="Stade de développement (ex: jeune, adulte, vieux, senescent)")
parser.add_argument('--nom', type=str, required=True, help="Nom de l'espèce (ex: ACERUB, PLATAL, etc.)")
parser.add_argument('--clc_quartier', type=str, required=True, help="Nom exact du quartier")
parser.add_argument('--clc_secteur', type=str, required=True, help="Nom exact du secteur")
parser.add_argument('--clc_nbr_diag', type=int, required=True, help="Nombre de diagnostics")

args = parser.parse_args()

# CHARGEMENT DU MODÈLE (logs vers stderr uniquement)
print("Chargement du modèle...", file=sys.stderr)
model = joblib.load('modele_arbre.pkl')
print("Modèle chargé avec succès !", file=sys.stderr)

# CRÉATION DU DATAFRAME
nouveau_arbre = pd.DataFrame({
    'tronc_diam': [args.tronc_diam],
    'haut_tot': [args.haut_tot],
    'haut_tronc': [args.haut_tronc],
    'fk_stadedev': [args.fk_stadedev],
    'nom': [args.nom],
    'clc_quartier': [args.clc_quartier],
    'clc_secteur': [args.clc_secteur],
    'clc_nbr_diag': [args.clc_nbr_diag]
})

# PRÉDICTION (log vers stderr)
print("Prédiction en cours...", file=sys.stderr)
prediction = model.predict(nouveau_arbre)

# Seul le résultat final va sur stdout (récupéré par PHP)
print(f"{prediction[0]:.1f}")