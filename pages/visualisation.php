<?php

// Récupération des arbres — date récente en premier, sans date à la fin
$arbres = $pdo->query("
    SELECT id_arbre, nom, created_date, clc_quartier, clc_secteur,
           haut_tot, haut_tronc, tronc_diam, fk_etat, fk_stade_dev,
           lat, lon
    FROM arbre
    ORDER BY 
        CASE WHEN created_date IS NULL OR created_date = '' THEN 1 ELSE 0 END ASC,
        created_date DESC
")->fetchAll();

// Carte Python
$venvPython   = __DIR__ . '/../scripts_ia/venv/bin/python3';
$scriptPython = __DIR__ . '/../scripts_ia/visualisation/carte-visualisation.py';

$mapHtml  = shell_exec(escapeshellcmd("$venvPython $scriptPython") . ' 2>/dev/null');
$mapError = null;
if (empty($mapHtml)) {
    $mapError = shell_exec(escapeshellcmd("$venvPython $scriptPython") . ' 2>&1 1>/dev/null');
}
?>

<link rel="stylesheet" href="/style/style_visualisation.css">

<!-- Hero -->
<div class="container mt-4">
    <div class="visu-hero">
        <div class="px-4">
            <div class="d-flex align-items-center">
                <div class="hero-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div>
                    <h1><i class="fas fa-tree me-2" style="color:var(--green-accent);font-size:1.5rem;"></i>Visualisation géographique</h1>
                    <p>Explorez et interagissez avec le patrimoine arboré de Saint-Quentin</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    <!-- Tableau -->
    <div class="visu-card">
        <div class="visu-card-header">
            <i class="fas fa-table"></i>
            <span>Liste des arbres</span>
            <div class="tree-count"><?= count($arbres) ?> arbres</div>
        </div>
        <div class="visu-table-wrap">
            <table class="table mb-0" id="arbreTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Quartier</th>
                        <th>Secteur</th>
                        <th>Hauteur (m)</th>
                        <th>Ø tronc</th>
                        <th>État</th>
                        <th>Stade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arbres as $arbre):
                        $etatClass = match($arbre['fk_etat']) {
                            'EN PLACE'  => 'etat-en-place',
                            'ABATTU'    => 'etat-abattu',
                            'REMPLACÉ'  => 'etat-remplace',
                            'SUPPRIMÉ'  => 'etat-supprime',
                            default     => 'etat-autre',
                        };
                    ?>
                        <tr class="arbre-row"
                            data-lat="<?= $arbre['lat'] ?>"
                            data-lon="<?= $arbre['lon'] ?>"
                            data-id="<?= $arbre['id_arbre'] ?>"
                            data-nom="<?= htmlspecialchars($arbre['nom'] ?? '—') ?>">
                            <td><?= $arbre['id_arbre'] ?></td>
                            <td><?= htmlspecialchars($arbre['nom'] ?? '—') ?></td>
                            <td>
                                <?php if (!empty($arbre['created_date'])): ?>
                                    <?= $arbre['created_date'] ?>
                                <?php else: ?>
                                    <span style="color:#ccc; font-style:italic;">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($arbre['clc_quartier']) ?></td>
                            <td><?= htmlspecialchars($arbre['clc_secteur']) ?></td>
                            <td><?= $arbre['haut_tot'] ?></td>
                            <td><?= $arbre['tronc_diam'] ?></td>
                            <td><span class="etat-badge <?= $etatClass ?>"><?= htmlspecialchars($arbre['fk_etat']) ?></span></td>
                            <td><?= htmlspecialchars($arbre['fk_stade_dev']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Carte -->
    <div class="visu-card">
        <div class="visu-card-header">
            <i class="fas fa-map"></i>
            <span>Carte interactive</span>
            <span id="selectedTree" class="selected-label"></span>
        </div>
        <div style="min-height:600px;">
            <?php if (!empty($mapHtml)): ?>
                <div id="mapContainer" style="width:100%; height:600px; overflow:hidden;">
                    <?= $mapHtml ?>
                </div>
            <?php else: ?>
                <div class="map-error">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors de la génération de la carte.<br>
                    <code style="font-size:0.72rem;"><?= nl2br(htmlspecialchars($mapError)) ?></code>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- CTA prédictions -->
    <div class="visu-cta">
        <div>
            <strong>Analyses prédictives disponibles</strong>
            <p>Utilisez les modèles IA pour estimer l'âge ou classifier un arbre par cluster.</p>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <a href="predire_age" class="btn-cta-green">
                <i class="fas fa-brain"></i>Prédiction de l'âge
            </a>
            <a href="predire_cluster" class="btn-cta-outline">
                <i class="fas fa-layer-group"></i>Prédiction cluster
            </a>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.arbre-row').forEach(function (row) {
        row.addEventListener('click', function () {
            document.querySelectorAll('.arbre-row').forEach(r => r.classList.remove('row-selected'));
            this.classList.add('row-selected');

            const lat = parseFloat(this.dataset.lat);
            const lon = parseFloat(this.dataset.lon);
            const nom = this.dataset.nom;

            document.getElementById('selectedTree').textContent = '📍 ' + nom;

            const mapDiv = document.getElementById('mapContainer')?.querySelector('.plotly-graph-div');
            if (mapDiv) {
                Plotly.relayout(mapDiv, {
                    'map.center': { lat: lat, lon: lon },
                    'map.zoom': 16
                });
            }
        });
    });
});
</script>