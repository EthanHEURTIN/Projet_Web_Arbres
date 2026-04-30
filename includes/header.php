<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrimoine Arboré - Saint-Quentin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    
    <style>
        body { font-family: 'Montserrat', sans-serif; }

        .custom-header {
            background: linear-gradient(135deg, #004d00 0%, #002b00 100%);
            height: 90px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .logo-box {
            background-color: white;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 30px;
            clip-path: polygon(0 0, 100% 0, 80% 100%, 0% 100%);
            z-index: 10;
        }

        .logo-box img {
            height: 85px;
            width: auto;
            transition: transform 0.3s ease;
        }

        /* Menu de navigation */
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            padding: 0.5rem 1.2rem !important;
            transition: all 0.3s;
        }

        .nav-link i {
            margin-right: 8px;
            color: #8dbb8d;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }

        /* Flèche du dropdown personnalisée */
        .nav-link.dropdown-toggle::after {
            border: none;
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.75rem;
            vertical-align: middle;
            margin-left: 4px;
            color: #8dbb8d;
            transition: transform 0.2s;
        }
        .nav-link.dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        /* Menu déroulant */
        .pred-dropdown {
            background: linear-gradient(160deg, #004d00, #002b00);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            padding: 6px;
            min-width: 160px;
            margin-top: 6px !important;
        }

        .pred-dropdown-item {
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            font-size: 0.88rem;
            color: rgba(255,255,255,0.85) !important;
            border-radius: 8px;
            padding: 9px 14px !important;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        .pred-dropdown-item i {
            color: #8dbb8d;
            font-size: 0.85rem;
            width: 16px;
            text-align: center;
        }

        .pred-dropdown-item:hover {
            background: rgba(255,255,255,0.12) !important;
            color: #fff !important;
        }

        /* Barre de recherche personnalisée */
        .search-box {
            position: relative;
            max-width: 250px;
        }

        .search-box input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 20px;
            padding-left: 35px;
        }

        .search-box input::placeholder { color: rgba(255,255,255,0.5); }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>

<header class="container-fluid p-0 sticky-top">
    <div class="d-flex align-items-center justify-content-between custom-header">
        
        <div class="d-flex align-items-center h-100">
            <div class="logo-box">
                <img src="assets/img/logo.png" alt="Logo">
            </div>

            <nav class="ms-4 d-none d-lg-block">
                <ul class="nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil"><i class="fas fa-home"></i>Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="visualisation"><i class="fas fa-tree"></i>Visualisation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ajout"><i class="fas fa-plus-circle"></i>Ajout</a>
                    </li>

                    <!-- Dropdown Prédiction -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-brain"></i>Prédiction
                        </a>
                        <ul class="dropdown-menu pred-dropdown">
                            <li>
                                <a class="dropdown-item pred-dropdown-item" href="predire_age">
                                    <i class="fas fa-seedling"></i>Âge
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item pred-dropdown-item" href="predire_cluster">
                                    <i class="fas fa-layer-group"></i>Cluster
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</header>