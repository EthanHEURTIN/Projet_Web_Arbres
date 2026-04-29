<?php

$venvPython   = __DIR__ . '/../scripts_ia/venv/bin/python3';
$scriptPython = __DIR__ . '/../scripts_ia/cluster/prediction_cluster.py';

$result    = null;
$mapHtml   = null;
$error     = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hauteur  = floatval($_POST['hauteur']);
    $diametre = floatval($_POST['diametre']);
    $age      = floatval($_POST['age']);

    if ($hauteur > 0 && $diametre > 0 && $age > 0) {
        $cmd    = escapeshellcmd("$venvPython $scriptPython") 
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
                . ' 2>&1'
            );
        }
    } else {
        $error = "Veuillez remplir tous les champs avec des valeurs positives.";
    }
}

// Couleurs et icônes selon la catégorie
$styles = [
    "Petit / Jeune"          => ['color' => 'success', 'icon' => 'bi-tree',        'bg' => '#d4edda'],
    "Moyen / Intermédiaire"  => ['color' => 'warning', 'icon' => 'bi-tree-fill',   'bg' => '#fff3cd'],
    "Grand / Mature"         => ['color' => 'danger',  'icon' => 'bi-tree-fill',   'bg' => '#f8d7da'],
];
$style = $result ? ($styles[$result] ?? ['color' => 'secondary', 'icon' => 'bi-question-circle', 'bg' => '#e2e3e5']) : null;
?>

<div class="container my-5">
    <h2 class="text-success fw-bold mb-1">Prédiction de Cluster</h2>
    <p class="text-muted mb-4">Entrez les caractéristiques d'un arbre pour identifier sa catégorie de croissance.</p>

    <!-- Formulaire -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white fw-bold">
            <i class="bi bi-sliders"></i> Paramètres de l'arbre
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="hauteur" class="form-label fw-bold">
                            <i class="bi bi-arrow-up text-success"></i> Hauteur totale (m)
                        </label>
                        <input type="number" step="0.1" min="0" 
                               class="form-control shadow-sm" 
                               id="hauteur" name="hauteur" 
                               placeholder="ex : 12.5"
                               value="<?php echo isset($_POST['hauteur']) ? htmlspecialchars($_POST['hauteur']) : ''; ?>"
                               required>
                    </div>
                    <div class="col-md-4">
                        <label for="diametre" class="form-label fw-bold">
                            <i class="bi bi-circle text-success"></i> Diamètre du tronc (m)
                        </label>
                        <input type="number" step="0.01" min="0" 
                               class="form-control shadow-sm" 
                               id="diametre" name="diametre" 
                               placeholder="ex : 0.45"
                               value="<?php echo isset($_POST['diametre']) ? htmlspecialchars($_POST['diametre']) : ''; ?>"
                               required>
                    </div>
                    <div class="col-md-4">
                        <label for="age" class="form-label fw-bold">
                            <i class="bi bi-calendar3 text-success"></i> Âge estimé (années)
                        </label>
                        <input type="number" step="1" min="0" 
                               class="form-control shadow-sm" 
                               id="age" name="age" 
                               placeholder="ex : 35"
                               value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>"
                               required>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                        <i class="bi bi-cpu"></i> Lancer la prédiction
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultat -->
    <?php if ($error): ?>
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo nl2br(htmlspecialchars($error)); ?>
        </div>
    <?php endif; ?>

    <?php if ($result && $style): ?>
        <div class="card shadow border-0 mb-5" style="background-color: <?php echo $style['bg']; ?>;">
            <div class="card-body text-center py-4">
                <i class="bi <?php echo $style['icon']; ?> text-<?php echo $style['color']; ?>" 
                   style="font-size: 3rem;"></i>
                <h3 class="mt-3 fw-bold text-<?php echo $style['color']; ?>">
                    Catégorie identifiée
                </h3>
                <div class="display-6 fw-bold mt-2">
                    <?php echo htmlspecialchars($result); ?>
                </div>
                <p class="text-muted mt-3 mb-0">
                    Résultat basé sur le modèle K-Means (k=3) entraîné sur le patrimoine arboré de Saint-Quentin.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Carte des clusters -->
    <?php if ($mapHtml): ?>
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-map"></i> Carte de segmentation du patrimoine arboré (k=3)
            </div>
            <div class="card-body p-0">
                <div style="width:100%; height:600px; overflow:hidden;">
                    <?php echo $mapHtml; ?>
                </div>
            </div>
        </div>

        <!-- Légende -->
        <div class="row text-center mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #2ca02c !important;">
                    <i class="bi bi-tree" style="font-size:1.8rem; color:#2ca02c;"></i>
                    <h6 class="fw-bold mt-2 mb-1" style="color:#2ca02c;">Petit / Jeune</h6>
                    <small class="text-muted">Faible hauteur, faible diamètre, jeune âge</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #ffbf00 !important;">
                    <i class="bi bi-tree-fill" style="font-size:1.8rem; color:#ffbf00;"></i>
                    <h6 class="fw-bold mt-2 mb-1" style="color:#ffbf00;">Moyen / Intermédiaire</h6>
                    <small class="text-muted">Caractéristiques intermédiaires</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #d62728 !important;">
                    <i class="bi bi-tree-fill" style="font-size:1.8rem; color:#d62728;"></i>
                    <h6 class="fw-bold mt-2 mb-1" style="color:#d62728;">Grand / Mature</h6>
                    <small class="text-muted">Grande hauteur, large diamètre, âge avancé</small>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Boutons navigation -->
    <div class="row justify-content-center border-top pt-4">
        <div class="col-md-8 text-center">
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a href="visualisation" class="btn btn-outline-success btn-lg px-4 shadow">
                    <i class="bi bi-map"></i> Carte générale
                </a>
                <a href="predire_age" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="bi bi-calendar3"></i> Prédiction de l'Âge
                </a>
            </div>
        </div>
    </div>
</div>