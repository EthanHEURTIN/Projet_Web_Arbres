import pickle
import numpy as np
import pandas as pd
import sqlite3
import plotly.express as px
import os
import sys
import json
import warnings

warnings.filterwarnings("ignore")

script_dir = os.path.dirname(os.path.abspath(__file__))

def predict_cluster(h, d, a, k=3):
    model_path  = os.path.join(script_dir, f'../cluster/kmeans_k{k}.pkl')
    scaler_path = os.path.join(script_dir, '../cluster/scaler_arbres.pkl')

    with open(model_path, 'rb') as f:
        model = pickle.load(f)
    with open(scaler_path, 'rb') as f:
        scaler = pickle.load(f)

    donnees_scaled = scaler.transform(np.array([[h, d, a]]))
    cluster_id = model.predict(donnees_scaled)[0]

    noms_clusters = {
        2: {0: "Grand / Mature",        1: "Petit / Jeune"},
        3: {0: "Grand / Mature",        1: "Petit / Jeune", 2: "Moyen / Intermédiaire"}
    }

    return noms_clusters[k].get(cluster_id, f"Cluster {cluster_id}")


def generate_map():
    model_path  = os.path.join(script_dir, '../cluster/kmeans_k3.pkl')
    scaler_path = os.path.join(script_dir, '../cluster/scaler_arbres.pkl')
    db_path     = os.path.join(script_dir, '../../arbres.db')

    with open(model_path, 'rb') as f:
        model = pickle.load(f)
    with open(scaler_path, 'rb') as f:
        scaler = pickle.load(f)

    conn = sqlite3.connect(db_path)
    df = pd.read_sql_query(
        "SELECT lat, lon, haut_tot, tronc_diam, age_estim FROM arbre WHERE age_estim IS NOT NULL",
        conn
    )
    conn.close()

    features = df[['haut_tot', 'tronc_diam', 'age_estim']].values
    scaled   = scaler.transform(features)
    clusters = model.predict(scaled)

    # Tri des clusters par hauteur moyenne pour mapping cohérent
    df['cluster_brut'] = clusters
    moyennes   = df.groupby('cluster_brut')['haut_tot'].mean().sort_values()
    ids_tries  = moyennes.index.tolist()

    mapping = {
        ids_tries[0]: "Petit / Jeune",
        ids_tries[1]: "Moyen / Intermédiaire",
        ids_tries[2]: "Grand / Mature"
    }
    df['categorie_arbre'] = df['cluster_brut'].map(mapping)

    fig = px.scatter_map(df,
                         lat="lat",
                         lon="lon",
                         color="categorie_arbre",
                         color_discrete_map={
                             "Petit / Jeune":           "#2ca02c",
                             "Moyen / Intermédiaire":   "#ffbf00",
                             "Grand / Mature":          "#d62728"
                         },
                         category_orders={"categorie_arbre": [
                             "Petit / Jeune",
                             "Moyen / Intermédiaire",
                             "Grand / Mature"
                         ]},
                         zoom=13,
                         height=600,
                         hover_data={'haut_tot': True, 'lat': False, 'lon': False})

    fig.update_layout(map_style="carto-positron")
    fig.update_layout(margin={"r":0,"t":0,"l":0,"b":0})

    return fig.to_html(full_html=False, include_plotlyjs='cdn')


# Point d'entrée : appelé par PHP avec les arguments
if __name__ == '__main__':
    h = float(sys.argv[1])
    d = float(sys.argv[2])
    a = float(sys.argv[3])

    categorie = predict_cluster(h, d, a, k=3)
    map_html  = generate_map()

    result = {
        "categorie": categorie,
        "map_html":  map_html
    }

    print(json.dumps(result, ensure_ascii=False))