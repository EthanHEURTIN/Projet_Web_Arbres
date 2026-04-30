<?php
require_once 'includes/db.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'accueil';

// ── Routes AJAX : répondent directement, sans header ni footer ──
if ($url === 'ajax_predire_age') {
    require 'pages/ajax_predire_age.php';
    exit;
}

if ($url === 'ajax_predire_cluster') {
    require 'pages/ajax_predire_cluster.php';
    exit;
}

// ── Layout normal ──
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
    default:
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}

echo '</div>';

include 'includes/footer.php';
?>