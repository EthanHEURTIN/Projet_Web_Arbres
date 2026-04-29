<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrimoine Arboré - Saint-Quentin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            height: 85px; /* On profite de la hauteur car c'est un rond */
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
            color: #8dbb8d; /* Vert clair pour les icônes */
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
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
                        <a class="nav-link" href="index.php?url=accueil"><i class="fas fa-home"></i>Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=visualisation"><i class="fas fa-tree"></i>Visualisation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=ajout"><i class="fas fa-plus-circle"></i>Ajout</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="d-flex align-items-center pe-4 gap-3">
            
            <a href="index.php?url=admin" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                <i class="fas fa-user-shield me-2"></i>Admin
            </a>
        </div>
        
    </div>
</header>