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

<style>
    /* ── Variables ── */
    :root {
        --green-dark:   #002b00;
        --green-mid:    #004d00;
        --green-soft:   #006400;
        --green-accent: #8dbb8d;
        --green-light:  #e8f5e8;
    }

    /* ── Hero banner ── */
    .pred-hero {
        background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-dark) 100%);
        padding: 48px 0 36px;
        margin-bottom: 40px;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .pred-hero::before {
        content: '\f1bb';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -30px;
        top: -20px;
        font-size: 200px;
        color: rgba(255,255,255,0.04);
        pointer-events: none;
        line-height: 1;
    }
    .pred-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        color: #fff;
        letter-spacing: 1px;
        margin: 0;
    }
    .pred-hero p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: rgba(255,255,255,0.55);
        margin: 6px 0 0;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .pred-hero .hero-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--green-accent);
        margin-right: 18px;
        flex-shrink: 0;
    }

    /* ── Cards ── */
    .pred-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 28px;
        height: 100%;
    }
    .pred-card-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pred-card-header i {
        color: var(--green-accent);
        font-size: 0.95rem;
    }
    .pred-card-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .pred-card-body {
        padding: 28px 32px;
    }

    /* ── Form fields ── */
    .pred-field {
        margin-bottom: 20px;
    }
    .pred-field label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #555;
        margin-bottom: 6px;
        display: block;
    }
    .pred-field label i {
        color: var(--green-soft);
        margin-right: 6px;
        width: 14px;
        text-align: center;
    }
    .pred-field .form-control,
    .pred-field .form-select {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: #fafafa;
        color: #333;
    }
    .pred-field .form-control:focus,
    .pred-field .form-select:focus {
        border-color: var(--green-soft);
        box-shadow: 0 0 0 3px rgba(0,100,0,0.1);
        background-color: #fff;
        outline: none;
    }

    /* ── Buttons ── */
    .pred-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 8px;
    }
    .btn-pred-back {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 11px 28px;
        border-radius: 10px;
        border: 1.5px solid #ddd;
        background: #fff;
        color: #666;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-pred-back:hover {
        border-color: #bbb;
        background: #f5f5f5;
        color: #444;
    }
    .btn-pred-submit {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 11px 32px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, var(--green-mid), var(--green-soft));
        color: #fff;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(0,77,0,0.25);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-pred-submit:hover {
        box-shadow: 0 6px 20px rgba(0,77,0,0.35);
        transform: translateY(-1px);
        color: #fff;
    }

    /* ── Result box ── */
    .pred-result {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    .pred-result-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pred-result-header i { color: var(--green-accent); font-size: 0.95rem; }
    .pred-result-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .pred-result-body {
        background: #fff;
        min-height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 32px;
    }

    /* Waiting state */
    .pred-waiting {
        text-align: center;
        color: #bbb;
    }
    .pred-waiting i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 12px;
        color: #ddd;
    }
    .pred-waiting p {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        margin: 0;
    }

    /* Success state */
    .pred-success {
        text-align: center;
        animation: fadeInUp 0.5s ease both;
    }
    .pred-success .age-icon {
        font-size: 1.4rem;
        color: var(--green-accent);
        margin-bottom: 10px;
        display: block;
    }
    .pred-success .age-badge {
        display: inline-flex;
        align-items: baseline;
        gap: 8px;
        background: linear-gradient(135deg, var(--green-mid), var(--green-soft));
        border-radius: 20px;
        padding: 18px 44px;
        box-shadow: 0 8px 28px rgba(0,77,0,0.25);
        margin-bottom: 14px;
    }
    .pred-success .age-number {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 3.2rem;
        color: #fff;
        line-height: 1;
    }
    .pred-success .age-unit {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 1.1rem;
        color: var(--green-accent);
        letter-spacing: 1px;
    }
    .pred-success .age-label {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #888;
        margin: 0;
    }

    /* Error state */
    .pred-error {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        color: #c0392b;
        background: #fdf3f2;
        border: 1px solid #f5c6c2;
        border-radius: 10px;
        padding: 16px 20px;
        width: 100%;
        line-height: 1.7;
    }
    .pred-error i { margin-right: 6px; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

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