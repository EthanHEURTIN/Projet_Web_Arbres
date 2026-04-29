<?php
// On récupère les données pour les listes déroulantes depuis la base de données
// On suppose que ta table s'appelle 'arbres'
try {
    $quartiers = $pdo->query("SELECT DISTINCT clc_quartier FROM arbre ORDER BY clc_quartier")->fetchAll(PDO::FETCH_COLUMN);
    $secteurs = $pdo->query("SELECT DISTINCT clc_secteur FROM arbre ORDER BY clc_secteur")->fetchAll(PDO::FETCH_COLUMN);
    $especes = $pdo->query("SELECT DISTINCT nom FROM arbre ORDER BY nom")->fetchAll(PDO::FETCH_COLUMN);
    $stades = $pdo->query("SELECT DISTINCT fk_stade_dev FROM arbre ORDER BY fk_stade_dev")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>



<style>
    .prediction-title {
        background-color: #004d00;
        color: white;
        padding: 20px;
        display: inline-block;
        width: 100%;
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 40px;
    }
    .form-label {
        font-weight: 500;
        color: #555;
    }
    .result-box {
        background-color: #d3d3d3;
        color: white;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-top: 30px;
        text-align: center;
    }
    .btn-valider {
        background-color: #006400;
        border: none;
        color: white;
        padding: 10px 40px;
    }
    .btn-valider:hover {
        background-color: #004d00;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-10">
        <h1 class="prediction-title">Prédiction de l'âge</h1>

        <form action="" method="POST">
            <div class="row g-3">
                <div class="col-md-6 offset-md-1">
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Hauteur totale:</label>
                        <div class="col-sm-7"><input type="number" step="0.01" class="form-control" name="hauteur_totale"></div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Diamètre du tronc:</label>
                        <div class="col-sm-7"><input type="number" step="0.01" class="form-control" name="diametre"></div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Hauteur tronc:</label>
                        <div class="col-sm-7"><input type="number" step="0.01" class="form-control" name="hauteur_tronc"></div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Nombre de diagnostique:</label>
                        <div class="col-sm-7"><input type="number" class="form-control" name="nb_diag"></div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Stade de développement:</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="stade">
                                <option selected disabled>Liste déroulante</option>
                                <?php foreach($stades as $s): ?>
                                    <option value="<?= $s ?>"><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Quartier:</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="quartier">
                                <option selected disabled>Liste déroulante</option>
                                <?php foreach($quartiers as $q): ?>
                                    <option value="<?= $q ?>"><?= $q ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Secteur:</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="secteur">
                                <option selected disabled>Liste déroulante</option>
                                <?php foreach($secteurs as $sec): ?>
                                    <option value="<?= $sec ?>"><?= $sec ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-5 form-label">Espèce:</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="espece">
                                <option selected disabled>Liste déroulante</option>
                                <?php foreach($especes as $e): ?>
                                    <option value="<?= $e ?>"><?= $e ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end pe-5 mt-4">
                    <button type="button" class="btn btn-secondary me-2 px-4" onclick="history.back()">RETOUR</button>
                    <button type="submit" class="btn btn-valider">Valider</button>
                </div>
            </div>
        </form>

        <div class="result-box">
            <?php 
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $diametre = $_POST['diametre'] ?? 0;
                    $haut_tot = $_POST['hauteur_totale'] ?? 0;
                    $haut_tronc = $_POST['hauteur_tronc'] ?? 0;
                    $stade = $_POST['stade'] ?? '';
                    $espece = $_POST['espece'] ?? '';
                    $quartier = $_POST['quartier'] ?? '';
                    $secteur = $_POST['secteur'] ?? '';
                    $nb_diag = $_POST['nb_diag'] ?? 0;

                    $python_path = "/var/www/html/arbres/scripts_ia/venv/bin/python3";
                    $script_path = "/var/www/html/arbres/scripts_ia/age/prediction_age.py";

                    $cmd = "$python_path $script_path " .
                        "--tronc_diam " . floatval($diametre) . " " .
                        "--haut_tot " . floatval($haut_tot) . " " .
                        "--haut_tronc " . floatval($haut_tronc) . " " .
                        "--fk_stadedev " . escapeshellarg($stade) . " " .
                        "--nom " . escapeshellarg($espece) . " " .
                        "--clc_quartier " . escapeshellarg($quartier) . " " .
                        "--clc_secteur " . escapeshellarg($secteur) . " " .
                        "--clc_nbr_diag " . intval($nb_diag) . " 2>&1";


                    $output = shell_exec($cmd);

                    if ($output) {
                        $lines = explode("\n", trim($output));
                        $resultat = end($lines); 
                        
                        if (is_numeric($resultat)) {
                            echo "🌳 Âge estimé : &nbsp;<strong>" . htmlspecialchars($resultat) . " ans</strong>";
                        } else {
                            echo "<div style='font-size:0.8rem; color:red;'>" . nl2br(htmlspecialchars($output)) . "</div>";
                        }
                    } else {
                        echo "Erreur d'exécution.";
                    }
                } else {
                    echo "En attente de données...";
                }
            ?>
        </div>
        <br><br><br>
    </div>
</div>