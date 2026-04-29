<?php

$venvPython   = __DIR__ . '/../scripts_ia/venv/bin/python3';
$scriptPython = __DIR__ . '/../scripts_ia/cluster/prediction_cluster.py';

$result  = null;
$mapHtml = null;
$error   = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hauteur  = floatval($_POST['hauteur']  ?? 0);
    $diametre = floatval($_POST['diametre'] ?? 0);
    $age      = floatval($_POST['age']      ?? 0);

    if ($hauteur > 0 && $diametre > 0 && $age > 0) {
        $cmd = escapeshellcmd("$venvPython $scriptPython")
             . ' ' . escapeshellarg($hauteur)
             . ' ' . escapeshellarg($diametre)
             . ' ' . escapeshellarg($age)
             . ' 2>/dev/null';

        $output = shell_exec($cmd);
        $data   = json_decode($output, true);

        if ($data && isset($data['categorie'])) {
            $result  = $data['categorie'];
            $mapHtml = $data['map_html'];
        } else {
            $error = shell_exec(
                escapeshellcmd("$venvPython $scriptPython")
                . ' ' . escapeshellarg($hauteur)
                . ' ' . escapeshellarg($diametre)
                . ' ' . escapeshellarg($age)
                . ' 2>&1 1>/dev/null'
            );
        }
    } else {
        $error = "Veuillez remplir tous les champs avec des valeurs positives.";
    }
}

// Styles par catégorie
$styles = [
    "Petit / Jeune"         => ['accent' => '#2ca02c', 'bg' => '#f0faf0', 'icon' => 'fa-seedling',  'label' => 'Faible hauteur, faible diamètre, jeune âge'],
    "Moyen / Intermédiaire" => ['accent' => '#d97706', 'bg' => '#fefaf0', 'icon' => 'fa-tree',       'label' => 'Caractéristiques intermédiaires'],
    "Grand / Mature"        => ['accent' => '#c53030', 'bg' => '#fff5f5', 'icon' => 'fa-tree',       'label' => 'Grande hauteur, large diamètre, âge avancé'],
];
$style = $result ? ($styles[$result] ?? ['accent' => '#666', 'bg' => '#f5f5f5', 'icon' => 'fa-question-circle', 'label' => '']) : null;
?>

<style>
    :root {
        --green-dark:   #002b00;
        --green-mid:    #004d00;
        --green-soft:   #006400;
        --green-accent: #8dbb8d;
        --green-light:  #e8f5e8;
    }

    /* ── Hero ── */
    .cluster-hero {
        background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-dark) 100%);
        padding: 48px 0 36px;
        margin-bottom: 40px;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .cluster-hero::before {
        content: '\f1bb';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -30px; top: -20px;
        font-size: 200px;
        color: rgba(255,255,255,0.04);
        pointer-events: none;
        line-height: 1;
    }
    .cluster-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        color: #fff;
        letter-spacing: 1px;
        margin: 0;
    }
    .cluster-hero p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: rgba(255,255,255,0.55);
        margin: 6px 0 0;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .cluster-hero .hero-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: var(--green-accent);
        margin-right: 18px;
        flex-shrink: 0;
    }

    /* ── Cards ── */
    .cluster-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .cluster-card-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .cluster-card-header i { color: var(--green-accent); font-size: 0.95rem; }
    .cluster-card-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .cluster-card-body { padding: 28px 32px; }

    /* ── Fields ── */
    .cluster-field { margin-bottom: 0; }
    .cluster-field label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #555;
        margin-bottom: 6px;
        display: block;
    }
    .cluster-field label i { color: var(--green-soft); margin-right: 6px; width: 14px; text-align: center; }
    .cluster-field .form-control {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: #fafafa;
        color: #333;
    }
    .cluster-field .form-control:focus {
        border-color: var(--green-soft);
        box-shadow: 0 0 0 3px rgba(0,100,0,0.1);
        background-color: #fff;
        outline: none;
    }

    /* ── Buttons ── */
    .cluster-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 8px;
    }
    .btn-cluster-back {
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-cluster-back:hover { border-color: #bbb; background: #f5f5f5; color: #444; }
    .btn-cluster-submit {
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
    .btn-cluster-submit:hover {
        box-shadow: 0 6px 20px rgba(0,77,0,0.35);
        transform: translateY(-1px);
        color: #fff;
    }

    /* ── Result box ── */
    .cluster-result {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }
    .cluster-result-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .cluster-result-header i { color: var(--green-accent); font-size: 0.95rem; }
    .cluster-result-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .cluster-result-body {
        background: #fff;
        min-height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 32px;
    }

    /* Waiting state */
    .cluster-waiting { text-align: center; color: #bbb; }
    .cluster-waiting i { font-size: 2.5rem; display: block; margin-bottom: 12px; color: #ddd; }
    .cluster-waiting p {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        margin: 0;
    }

    /* Success state */
    .cluster-success {
        text-align: center;
        animation: fadeInUp 0.5s ease both;
    }
    .cluster-success .cat-icon {
        font-size: 1.4rem;
        display: block;
        margin-bottom: 10px;
    }
    .cluster-success .cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        border-radius: 20px;
        padding: 18px 44px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.15);
        margin-bottom: 14px;
    }
    .cluster-success .cat-name {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.6rem;
        line-height: 1;
    }
    .cluster-success .cat-label {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #888;
        margin: 0;
    }
    .cluster-success .cat-desc {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        color: #aaa;
        margin: 6px 0 0;
    }

    /* Error state */
    .cluster-error {
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
    .cluster-error i { margin-right: 6px; }

    /* ── Map card ── */
    .map-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .map-card-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .map-card-header i { color: var(--green-accent); font-size: 0.95rem; }
    .map-card-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* ── Legend cards ── */
    .legend-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .legend-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .legend-dot {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .legend-card .legend-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        margin-bottom: 4px;
    }
    .legend-card .legend-desc {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        color: #888;
        margin: 0;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<!-- Hero -->
<div class="container mt-4">
    <div class="cluster-hero">
        <div class="px-4">
            <div class="d-flex align-items-center">
                <div class="hero-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h1><i class="fas fa-seedling me-2" style="color:var(--green-accent);font-size:1.5rem;"></i>Prédiction de cluster</h1>
                    <p>Identifiez la catégorie de croissance d'un arbre par le modèle K-Means (k=3)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    <!-- Formulaire -->
    <form action="" method="POST">
        <div class="cluster-card">
            <div class="cluster-card-header">
                <i class="fas fa-sliders-h"></i>
                <span>Paramètres de l'arbre</span>
            </div>
            <div class="cluster-card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="cluster-field">
                            <label><i class="fas fa-arrows-alt-v"></i>Hauteur totale (m)</label>
                            <input type="number" step="0.1" min="0" class="form-control"
                                   name="hauteur" placeholder="ex : 12.5"
                                   value="<?= htmlspecialchars($_POST['hauteur'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="cluster-field">
                            <label><i class="fas fa-circle-notch"></i>Diamètre du tronc (m)</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   name="diametre" placeholder="ex : 0.45"
                                   value="<?= htmlspecialchars($_POST['diametre'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="cluster-field">
                            <label><i class="fas fa-hourglass-half"></i>Âge estimé (années)</label>
                            <input type="number" step="1" min="0" class="form-control"
                                   name="age" placeholder="ex : 35"
                                   value="<?= htmlspecialchars($_POST['age'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cluster-actions mb-4">
            <a href="javascript:history.back()" class="btn-cluster-back">
                <i class="fas fa-arrow-left"></i>Retour
            </a>
            <button type="submit" class="btn-cluster-submit">
                <i class="fas fa-bolt"></i>Lancer la prédiction
            </button>
        </div>
    </form>

    <!-- Résultat -->
    <div class="cluster-result">
        <div class="cluster-result-header">
            <i class="fas fa-chart-pie"></i>
            <span>Résultat de la classification</span>
        </div>
        <div class="cluster-result-body">
            <?php if ($error): ?>
                <div class="cluster-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= nl2br(htmlspecialchars($error)) ?>
                </div>

            <?php elseif ($result && $style): ?>
                <div class="cluster-success">
                    <i class="fas <?= $style['icon'] ?> cat-icon" style="color:<?= $style['accent'] ?>"></i>
                    <div class="cat-badge" style="background:<?= $style['bg'] ?>; border: 2px solid <?= $style['accent'] ?>20;">
                        <span class="cat-name" style="color:<?= $style['accent'] ?>"><?= htmlspecialchars($result) ?></span>
                    </div>
                    <p class="cat-label">Catégorie identifiée par le modèle IA</p>
                    <p class="cat-desc"><?= htmlspecialchars($style['label']) ?></p>
                </div>

            <?php else: ?>
                <div class="cluster-waiting">
                    <i class="fas fa-layer-group"></i>
                    <p>Remplissez le formulaire et lancez la prédiction</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Carte des clusters -->
    <?php if ($mapHtml): ?>
        <div class="map-card">
            <div class="map-card-header">
                <i class="fas fa-map-marked-alt"></i>
                <span>Carte de segmentation du patrimoine arboré (k=3)</span>
            </div>
            <div style="width:100%; height:600px; overflow:hidden;">
                <?= $mapHtml ?>
            </div>
        </div>

        <!-- Légende -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="legend-card" style="border-left: 4px solid #2ca02c;">
                    <div class="legend-dot" style="background:#f0faf0; color:#2ca02c;">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div>
                        <div class="legend-title" style="color:#2ca02c;">Petit / Jeune</div>
                        <p class="legend-desc">Faible hauteur, faible diamètre, jeune âge</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="legend-card" style="border-left: 4px solid #d97706;">
                    <div class="legend-dot" style="background:#fefaf0; color:#d97706;">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div>
                        <div class="legend-title" style="color:#d97706;">Moyen / Intermédiaire</div>
                        <p class="legend-desc">Caractéristiques intermédiaires</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="legend-card" style="border-left: 4px solid #c53030;">
                    <div class="legend-dot" style="background:#fff5f5; color:#c53030;">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div>
                        <div class="legend-title" style="color:#c53030;">Grand / Mature</div>
                        <p class="legend-desc">Grande hauteur, large diamètre, âge avancé</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>