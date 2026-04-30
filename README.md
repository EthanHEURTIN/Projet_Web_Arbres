# Patrimoine Arboré — Saint-Quentin

> Plateforme web de gestion et d'analyse prédictive du patrimoine arboré urbain, développée pour la ville de Saint-Quentin.


---

## Objectif

Moderniser la gestion des arbres de la ville de Saint-Quentin en combinant cartographie interactive, base de données et modèles de Big Data. L'application permet de :

- Visualiser le patrimoine arboré sur une carte interactive
- Ajouter de nouveaux arbres via un formulaire connecté à la base
- Estimer l'âge d'un arbre à partir de ses mesures dendrométriques (Random Forest Regressor)
- Classifier un arbre par cluster morphologique (K-Means, k=3)

---

##  Arborescence

```
Projet_Web_Arbres/
├── assets/
│   └── img/
│       └── logo.png
├── includes/                  # Composants PHP communs
│   ├── db.php                 # Connexion PDO SQLite
│   ├── header.php             # En-tête + navigation
│   └── footer.php             # Pied de page + crédits
├── pages/                     # Pages de l'application
│   ├── accueil.php
│   ├── ajout.php
│   ├── visualisation.php
│   ├── predire_age.php
│   └── predire_cluster.php
├── scripts_db/                # Base de données et données initiales
│   ├── create_db.sql
│   ├── data.sql
│   ├── insert_data.py
│   └── Patrimoine_Arbore_Nettoye.csv
├── scripts_ia/                # Modèles IA et environnement Python
│   ├── age/                   # Random Forest Regressor — prédiction d'âge
│   ├── cluster/               # K-Means — segmentation par profil
│   ├── visualisation/         # Génération de cartes Plotly
│   ├── requirements.txt
│   └── add_venv.sh            # Installation de l'environnement
├── style/                     # Feuilles de style par page
├── arbres.db                  # Base SQLite
├── index.php                  # Point d'entrée
├── .htaccess                  # Réécriture d'URL
└── README.md
```

---

## Stack technique

### Backend & web

| Couche | Technologie |
|---|---|
| Serveur | PHP 8 |
| Base de données | SQLite (`arbres.db`) |
| Architecture | Front Controller (routeur via `index.php`) |
| Frontend | HTML5, CSS3 (Glassmorphism), Bootstrap 5, FontAwesome 6 |
| Cartographie | Plotly + Mapbox |

### Data & IA

| Usage | Technologie |
|---|---|
| Manipulation de données | Python 3, Pandas |
| Modèles ML | Scikit-Learn (Random Forest, K-Means) |
| Sérialisation | Joblib (`.pkl`) |
| Pré-traitement | ColumnTransformer + Pipeline scikit-learn |

---

## Modules d'intelligence artificielle

Les scripts Python sont appelés par PHP via `shell_exec()`. Chaque module charge un modèle pré-entraîné (jamais ré-entraîné à chaud) et renvoie un résultat JSON.

| Module | Algorithme | Entrée | Sortie |
|---|---|---|---|
| **Prédiction d'âge** | Random Forest Regressor | Mesures + espèce + localisation | Âge estimé en années |
| **Clustering** | K-Means (k=3) | Hauteur, diamètre, âge | Catégorie : *Petit / Moyen / Grand* |
| **Visualisation** | Plotly Mapbox | — | Carte HTML interactive |

---

## Routage

Le fichier `index.php` agit comme un **Front Controller**. Toutes les pages sont accessibles via le paramètre `url` :

```
index.php?url=accueil
index.php?url=visualisation
index.php?url=ajout
index.php?url=predire_age
index.php?url=predire_cluster
```

Le `.htaccess` permet la réécriture en URLs propres : `/visualisation`, `/ajout`, etc.

---

## Installation

### Prérequis

- PHP ≥ 8.0 avec extension `pdo_sqlite`
- Python ≥ 3.10
- Apache (ou serveur PHP intégré)

### 1. Cloner le projet

```bash
git clone <url-du-repo> Projet_Web_Arbres
cd Projet_Web_Arbres
```

### 2. Initialiser la base SQLite

```bash
sqlite3 arbres.db < scripts_db/create_db.sql
sqlite3 arbres.db < scripts_db/data.sql
```

### 3. Préparer l'environnement Python pour les modèles IA

```bash
cd scripts_ia
chmod +x add_venv.sh
./add_venv.sh
```

Le script crée le venv et installe les dépendances depuis `requirements.txt`.

### 4. Lancer l'application

**Avec Apache :** placer le projet dans `/var/www/html/` et accéder à `http://localhost/Projet_Web_Arbres`.

**Avec le serveur PHP intégré :**

```bash
php -S localhost:8080
```

Puis ouvrir [http://localhost:8080](http://localhost:8080).

---

## Schéma de la base

Une seule table `arbre` regroupe l'ensemble des caractéristiques (25 champs). Les principales colonnes :

- **Identité** : `id_arbre`, `nom`, `feuillage`, `remarquable`
- **Mesures** : `haut_tot`, `haut_tronc`, `tronc_diam`
- **Localisation** : `lat`, `lon`, `clc_quartier`, `clc_secteur`
- **État** : `fk_etat`, `fk_stade_dev`, `fk_port`, `fk_pied`, `fk_situation`
- **Métadonnées** : `created_date`, `age_estim`, `commentaire_environnement`

Les valeurs des champs `CHECK` (état, stade, situation, feuillage…) sont définies directement dans `create_db.sql`.



