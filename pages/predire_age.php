<?php
// Récupération des données pour les listes déroulantes depuis la base de données
try {
    $quartiers = $pdo->query("SELECT DISTINCT clc_quartier FROM arbre ORDER BY clc_quartier")->fetchAll(PDO::FETCH_COLUMN);
    $secteurs  = $pdo->query("SELECT DISTINCT clc_secteur FROM arbre ORDER BY clc_secteur")->fetchAll(PDO::FETCH_COLUMN);
    $especes   = $pdo->query("SELECT DISTINCT nom FROM arbre ORDER BY nom")->fetchAll(PDO::FETCH_COLUMN);
    $stades    = $pdo->query("SELECT DISTINCT fk_stade_dev FROM arbre ORDER BY fk_stade_dev")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<link rel="stylesheet" href="/style/style_predire_age.css">

<!-- Hero banner -->
<div class="container mt-4">
    <div class="pred-hero">
        <div class="px-4">
            <div class="d-flex align-items-center">
                <div class="hero-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <h1><i class="fas fa-seedling me-2" style="color:var(--green-accent);font-size:1.5rem;"></i>Prédiction de l'âge</h1>
                    <p>Estimez l'âge d'un arbre à partir de ses caractéristiques dendrométriques</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <form action="" method="POST">
        <div class="row g-4 align-items-stretch">

            <!-- Colonne gauche : mesures -->
            <div class="col-lg-6">
                <div class="pred-card">
                    <div class="pred-card-header">
                        <i class="fas fa-ruler-combined"></i>
                        <span>Mesures dendrométriques</span>
                    </div>
                    <div class="pred-card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="pred-field">
                                    <label><i class="fas fa-arrows-alt-v"></i>Hauteur totale</label>
                                    <input type="number" step="0.01" class="form-control" name="hauteur_totale" placeholder="ex : 12.5">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pred-field">
                                    <label><i class="fas fa-circle-notch"></i>Diamètre du tronc</label>
                                    <input type="number" step="0.01" class="form-control" name="diametre" placeholder="ex : 0.35">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pred-field">
                                    <label><i class="fas fa-ruler-vertical"></i>Hauteur du tronc</label>
                                    <input type="number" step="0.01" class="form-control" name="hauteur_tronc" placeholder="ex : 2.0">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pred-field">
                                    <label><i class="fas fa-clipboard-list"></i>Nb. de diagnostics</label>
                                    <input type="number" class="form-control" name="nb_diag" placeholder="ex : 3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : caractéristiques -->
            <div class="col-lg-6">
                <div class="pred-card">
                    <div class="pred-card-header">
                        <i class="fas fa-tags"></i>
                        <span>Caractéristiques</span>
                    </div>
                    <div class="pred-card-body">
                        <div class="pred-field">
                            <label><i class="fas fa-chart-line"></i>Stade de développement</label>
                            <select class="form-select" name="stade">
                                <option selected disabled value="">— Sélectionner —</option>
                                <?php foreach ($stades as $s): ?>
                                    <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pred-field">
                            <label><i class="fas fa-leaf"></i>Espèce</label>
                            <select class="form-select" name="espece">
                                <option selected disabled value="">— Sélectionner —</option>
                                <?php foreach ($especes as $e): ?>
                                    <option value="<?= htmlspecialchars($e) ?>"><?= htmlspecialchars($e) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="pred-field">
                                    <label><i class="fas fa-map-marker-alt"></i>Quartier</label>
                                    <select class="form-select" name="quartier">
                                        <option selected disabled value="">— Sélectionner —</option>
                                        <?php foreach ($quartiers as $q): ?>
                                            <option value="<?= htmlspecialchars($q) ?>"><?= htmlspecialchars($q) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pred-field">
                                    <label><i class="fas fa-map"></i>Secteur</label>
                                    <select class="form-select" name="secteur">
                                        <option selected disabled value="">— Sélectionner —</option>
                                        <?php foreach ($secteurs as $sec): ?>
                                            <option value="<?= htmlspecialchars($sec) ?>"><?= htmlspecialchars($sec) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Boutons -->
        <div class="pred-actions mb-4">
            <button type="button" class="btn-pred-back" onclick="history.back()">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </button>
            <button type="submit" class="btn-pred-submit">
                <i class="fas fa-bolt"></i>Lancer la prédiction
            </button>
        </div>
    </form>

    <!-- Zone de résultat -->
    <div class="pred-result">
        <div class="pred-result-header">
            <i class="fas fa-chart-bar"></i>
            <span>Résultat de la prédiction</span>
        </div>
        <div class="pred-result-body">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                        echo '
                        <div class="pred-success">
                            <i class="fas fa-tree age-icon"></i>
                            <div class="age-badge">
                                <span class="age-number">' . htmlspecialchars($resultat) . '</span>
                                <span class="age-unit">ans</span>
                            </div>
                            <p class="age-label">Âge estimé par le modèle IA</p>
                        </div>';
                    } else {
                        echo '<div class="pred-error"><i class="fas fa-exclamation-triangle"></i>'
                            . nl2br(htmlspecialchars($output)) . '</div>';
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
                    echo '<div class="pred-error"><i class="fas fa-exclamation-triangle"></i>'
                        . nl2br(htmlspecialchars($error ?: "Erreur inconnue — aucune sortie reçue."))
                        . '</div>';
                }

            } else {
                echo '
                <div class="pred-waiting">
                    <i class="fas fa-tree"></i>
                    <p>Remplissez le formulaire et lancez la prédiction</p>
                </div>';
            }
            ?>
        </div>
    </div>

</div>