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
<link rel="stylesheet" href="/style/style_predire_cluster.css">

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