<?php

// Récupération des arbres triés par date
$arbres = $pdo->query("
    SELECT id_arbre, nom, created_date, clc_quartier, clc_secteur, 
           haut_tot, haut_tronc, tronc_diam, fk_etat, fk_stade_dev, 
           remarquable, lat, lon
    FROM arbre 
    ORDER BY created_date ASC
")->fetchAll();

// Exécution du script Python pour la carte
$venvPython   = __DIR__ . '/../scripts_ia/venv/bin/python3';
$scriptPython = __DIR__ . '/../scripts_ia/visualisation/carte-visualisation.py';

$mapHtml  = shell_exec(escapeshellcmd("$venvPython $scriptPython") . ' 2>/dev/null');
$mapError = null;

if (empty($mapHtml)) {
    $mapError = shell_exec(escapeshellcmd("$venvPython $scriptPython") . ' 2>&1');
}
?>

<div class="container my-5">
    <h2 class="text-success fw-bold mb-4">Visualisation Géographique</h2>

    <!-- Tableau des arbres -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white fw-bold">
            <i class="bi bi-table"></i> Liste des arbres
            <span class="badge bg-light text-success ms-2"><?php echo count($arbres); ?> arbres</span>
        </div>
        <div class="card-body p-0">
            <div style="height: 320px; overflow-y: auto;">
                <table class="table table-hover table-sm table-striped mb-0" id="arbreTable">
                    <thead class="table-success sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Date</th>
                            <th>Quartier</th>
                            <th>Secteur</th>
                            <th>Hauteur (m)</th>
                            <th>Diamètre tronc</th>
                            <th>État</th>
                            <th>Stade</th>
                            <th>Remarquable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arbres as $arbre): ?>
                            <tr class="arbre-row" 
                                style="cursor:pointer;" 
                                data-lat="<?php echo $arbre['lat']; ?>" 
                                data-lon="<?php echo $arbre['lon']; ?>"
                                data-id="<?php echo $arbre['id_arbre']; ?>">
                                <td><?php echo $arbre['id_arbre']; ?></td>
                                <td><?php echo htmlspecialchars($arbre['nom'] ?? '—'); ?></td>
                                <td><?php echo $arbre['created_date'] ?? '—'; ?></td>
                                <td><?php echo htmlspecialchars($arbre['clc_quartier']); ?></td>
                                <td><?php echo htmlspecialchars($arbre['clc_secteur']); ?></td>
                                <td><?php echo $arbre['haut_tot']; ?></td>
                                <td><?php echo $arbre['tronc_diam']; ?></td>
                                <td>
                                    <?php
                                    $etatClass = match($arbre['fk_etat']) {
                                        'EN PLACE'      => 'success',
                                        'ABATTU'        => 'danger',
                                        'REMPLACÉ'      => 'warning',
                                        'SUPPRIMÉ'      => 'dark',
                                        default         => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?php echo $etatClass; ?>">
                                        <?php echo htmlspecialchars($arbre['fk_etat']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($arbre['fk_stade_dev']); ?></td>
                                <td class="text-center">
                                    <?php if ($arbre['remarquable']): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star text-muted"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Carte -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-success text-white fw-bold">
            <i class="bi bi-map"></i> Carte interactive
            <span id="selectedTree" class="ms-3 fw-normal fst-italic small"></span>
        </div>
        <div class="card-body p-0" style="min-height:600px;">
            <?php if (!empty($mapHtml)): ?>
                <div id="mapContainer" style="width:100%; height:600px; overflow:hidden;">
                    <?php echo $mapHtml; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger m-3">
                    Erreur lors de la génération de la carte.<br>
                    <pre><?php echo htmlspecialchars($mapError); ?></pre>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Boutons prédictions -->
    <div class="row justify-content-center border-top pt-4">
        <div class="col-md-8 text-center">
            <p class="text-muted mb-3">Analyses prédictives disponibles pour ces données :</p>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a href="predire_age" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="bi bi-calendar3"></i> Prédiction de l'Âge
                </a>
                <a href="predire_cluster" class="btn btn-info btn-lg px-4 text-white shadow">
                    <i class="bi bi-diagram-3"></i> Prédiction Cluster
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Surbrillance de la ligne sélectionnée
    document.querySelectorAll('.arbre-row').forEach(function (row) {
        row.addEventListener('click', function () {

            // Retirer la sélection précédente
            document.querySelectorAll('.arbre-row').forEach(r => r.classList.remove('table-primary'));
            this.classList.add('table-primary');

            const lat = parseFloat(this.dataset.lat);
            const lon = parseFloat(this.dataset.lon);
            const nom = this.querySelector('td:nth-child(2)').textContent;

            // Afficher le nom sélectionné
            document.getElementById('selectedTree').textContent = '📍 ' + nom;

            // Centrer la carte Plotly sur l'arbre sélectionné
            const mapDiv = document.getElementById('mapContainer').querySelector('.plotly-graph-div');
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