PROJET WEB – ARBRES
====================

Objectif
--------
Créer un site web permettant de visualiser et gérer les arbres remarquables
d'une ville (ex: Quimper). Le projet inclut :-
* Une page d'accueil
* Une carte interactive avec marqueurs
* Une page d'ajout d'arbres
* Des statistiques
* Une interface d'administration sécurisée

Structure du projet
--------------------

Arborescence des fichiers :
```
Projet_Web_Arbres/
├── config/           # Configuration connexion
│   ├── db.php
│   └── init.sql      # Schéma SQL
├── include/          # Scripts communs
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── pages/            # Pages principales
│   ├── index.php
│   ├── visualisation.php
│   ├── ajout.php
│   └── admin/
│       ├── index.php
│       ├── connection.php
│       └── déconnection.php
├── style/
│   ├── main.css
│   ├── style_accueil.css
│   ├── style_visualisation.css
│   ├── style_ajout.css
│   └── style_admin.css
└── assets/           # Images / Icônes
    └── favicon.ico
```

Composants principaux
---------------------
1. Page d'accueil :
   - Présentation
   - Accès rapides (carte, ajout)
   - Statistiques

2. Carte interactive :
   - Leaflet + markers des arbres
   - Filtres par quartier / secteur
   - Informations en popup

3. Ajout d'arbres :
   - Formulaire avec validation
   - Choix de catégorie, localisation, état
   - Auto-complétion des espèces (suggestion)

4. Administration :
   - Connexion sécurisée (hashage password)
   - Ajout/modification des catégories
   - Gestion des utilisateurs

5. Base de données :
   - Tables : arbre, categorie, etat, situation, stade_dev, port, pied
   - Requêtes optimisées
   - Constraints et indexes

Utilisation des technologies
--------------------------
* PHP 8+
* MySQL / MariaDB
* Leaflet + OpenStreetMap
* FontAwesome 5/6
* HTML5 + CSS3
* JavaScript ES6
* Bootstrap 5 (optionnel, pour layout)

Accès au site (si déployé)
-------------------------
* Page d'accueil : http://localhost/Projet_Web_Arbres/pages/index.php
* Carte : http://localhost/Projet_Web_Arbres/pages/visualisation.php
* Ajout : http://localhost/Projet_Web_Arbres/pages/ajout.php
* Admin : http://localhost/Projet_Web_Arbres/pages/admin/connection.php

Identifiants admin (par défaut dans init.sql)
-------------------------------------------
* Login : admin
* Password : [PASSWORD]