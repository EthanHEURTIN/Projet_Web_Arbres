import plotly.express as px
import pandas as pd
import sqlite3 # Ou mysql.connector si vous utilisez MySQL

# 1. Connexion à la base et récupération des données
# Remplacez par vos identifiants MySQL si nécessaire
conn = sqlite3.connect('arbres.db') 
query = "SELECT nom, lat, lon, haut_tot, clc_quartier FROM arbre"
df = pd.read_sql_query(query, conn)

# 2. Création de la carte avec Plotly
fig = px.scatter_mapbox(df, 
                        lat="lat", 
                        lon="lon", 
                        hover_name="nom", 
                        hover_data=["clc_quartier", "haut_tot"],
                        color_discrete_sequence=["fuchsia"], 
                        zoom=13, 
                        height=600)

fig.update_layout(mapbox_style="open-street-map")
fig.update_layout(margin={"r":0,"t":0,"l":0,"b":0})

# 3. Sauvegarde en fichier HTML
fig.write_html("map_plotly.html")
conn.close()