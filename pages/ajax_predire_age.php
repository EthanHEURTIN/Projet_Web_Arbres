<?php
// Point d'entrée AJAX pour la prédiction d'âge
// Appelé par fetch() depuis predire_age.php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}

$diametre   = $_POST['diametre']       ?? 0;
$haut_tot   = $_POST['hauteur_totale'] ?? 0;
$haut_tronc = $_POST['hauteur_tronc']  ?? 0;
$stade      = $_POST['stade']          ?? '';
$espece     = $_POST['espece']         ?? '';
$quartier   = $_POST['quartier']       ?? '';
$secteur    = $_POST['secteur']        ?? '';
$nb_diag    = $_POST['nb_diag']        ?? 0;

$script_path = __DIR__ . "/../scripts_ia/age/prediction_age.py";
$python_path = __DIR__ . "/../scripts_ia/venv/bin/python3";

$cmd = "$python_path $script_path "
    . "--tronc_diam "   . floatval($diametre)       . " "
    . "--haut_tot "     . floatval($haut_tot)       . " "
    . "--haut_tronc "   . floatval($haut_tronc)     . " "
    . "--fk_stadedev "  . escapeshellarg($stade)    . " "
    . "--nom "          . escapeshellarg($espece)   . " "
    . "--clc_quartier " . escapeshellarg($quartier) . " "
    . "--clc_secteur "  . escapeshellarg($secteur)  . " "
    . "--clc_nbr_diag " . intval($nb_diag)
    . " 2>/dev/null";

$output = shell_exec($cmd);

if ($output !== null && trim($output) !== '') {
    $resultat = trim($output);
    if (is_numeric($resultat)) {
        echo json_encode(['success' => true, 'age' => $resultat]);
    } else {
        echo json_encode(['error' => $resultat]);
    }
} else {
    $cmd_debug = "$python_path $script_path "
        . "--tronc_diam "   . floatval($diametre)       . " "
        . "--haut_tot "     . floatval($haut_tot)       . " "
        . "--haut_tronc "   . floatval($haut_tronc)     . " "
        . "--fk_stadedev "  . escapeshellarg($stade)    . " "
        . "--nom "          . escapeshellarg($espece)   . " "
        . "--clc_quartier " . escapeshellarg($quartier) . " "
        . "--clc_secteur "  . escapeshellarg($secteur)  . " "
        . "--clc_nbr_diag " . intval($nb_diag)
        . " 2>&1 1>/dev/null";
    $error = shell_exec($cmd_debug);
    echo json_encode(['error' => $error ?: 'Erreur inconnue — aucune sortie reçue.']);
}