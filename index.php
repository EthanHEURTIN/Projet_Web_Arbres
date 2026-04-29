<?php
require_once 'includes/db.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'accueil';

// Inclusion du Header (Bootstrap + Navbar)
include 'includes/header.php';

echo '<div class="container mt-4">';

switch ($url) {
    case 'accueil':
    case '':
        include 'pages/accueil.php';
        break;
    case 'ajout':
        include 'pages/ajout.php';
        break;
    case 'visualisation':
        include 'pages/visualisation.php';
        break;
    case 'predire_cluster':
        include 'pages/predire_cluster.php';
        break;
    case 'predire_age':
        include 'pages/predire_age.php';
        break;
    case 'ajout':
        include 'pages/ajout.php';
        break;
    default:
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}

echo '</div>';

// Inclusion du Footer (Scripts JS)
include 'includes/footer.php';
?>