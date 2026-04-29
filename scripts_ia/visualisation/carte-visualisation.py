import plotly.express as px
import pandas as pd
import sqlite3
import os

script_dir = os.path.dirname(os.path.abspath(__file__))
db_path = os.path.join(script_dir, '../../arbres.db')

conn = sqlite3.connect(db_path)
query = "SELECT id_arbre, nom, lat, lon, haut_tot, clc_quartier FROM arbre"
df = pd.read_sql_query(query, conn)
conn.close()

fig = px.scatter_map(df,
                     lat="lat",
                     lon="lon",
                     hover_name="nom",
                     hover_data=["clc_quartier", "haut_tot"],
                     color_discrete_sequence=["fuchsia"],
                     zoom=13,
                     height=600,
                     custom_data=["id_arbre", "lat", "lon"])

fig.update_layout(map_style="open-street-map")
fig.update_layout(margin={"r":0,"t":0,"l":0,"b":0})

print(fig.to_html(full_html=False, include_plotlyjs='cdn'))