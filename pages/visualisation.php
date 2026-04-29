<?php

// Récupération des arbres
$arbres = $pdo->query("
    SELECT id_arbre, nom, created_date, clc_quartier, clc_secteur,
           haut_tot, haut_tronc, tronc_diam, fk_etat, fk_stade_dev,
           remarquable, lat, lon
    FROM arbre
    ORDER BY created_date ASC
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

<style>
    :root {
        --green-dark:   #002b00;
        --green-mid:    #004d00;
        --green-soft:   #006400;
        --green-accent: #8dbb8d;
        --green-light:  #e8f5e8;
    }

    /* ── Hero ── */
    .visu-hero {
        background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-dark) 100%);
        padding: 48px 0 36px;
        margin-bottom: 40px;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .visu-hero::before {
        content: '\f5a0';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -30px; top: -20px;
        font-size: 200px;
        color: rgba(255,255,255,0.04);
        pointer-events: none;
        line-height: 1;
    }
    .visu-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        color: #fff;
        letter-spacing: 1px;
        margin: 0;
    }
    .visu-hero p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: rgba(255,255,255,0.55);
        margin: 6px 0 0;
        font-size: 0.9rem;
    }
    .visu-hero .hero-icon {
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
    .visu-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .visu-card-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .visu-card-header i { color: var(--green-accent); font-size: 0.95rem; }
    .visu-card-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .visu-card-header .tree-count {
        margin-left: auto;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(255,255,255,0.15);
        color: #fff;
        padding: 3px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    /* ── Table ── */
    .visu-table-wrap {
        height: 340px;
        overflow-y: auto;
    }
    .visu-table-wrap::-webkit-scrollbar { width: 6px; }
    .visu-table-wrap::-webkit-scrollbar-track { background: #f5f5f5; }
    .visu-table-wrap::-webkit-scrollbar-thumb { background: var(--green-accent); border-radius: 3px; }

    #arbreTable {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        margin: 0;
    }
    #arbreTable thead th {
        position: sticky;
        top: 0;
        background: var(--green-light);
        color: var(--green-mid);
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border: none;
        padding: 10px 14px;
        white-space: nowrap;
        z-index: 1;
    }
    #arbreTable tbody tr {
        cursor: pointer;
        transition: background 0.15s;
        border-bottom: 1px solid #f5f5f5;
    }
    #arbreTable tbody tr:hover { background: #f7fbf7; }
    #arbreTable tbody tr.row-selected { background: var(--green-light) !important; }
    #arbreTable tbody td {
        padding: 9px 14px;
        vertical-align: middle;
        color: #444;
        border: none;
    }

    /* Badges état */
    .etat-badge {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .etat-en-place  { background: #e8f5e8; color: var(--green-soft); }
    .etat-abattu    { background: #fdf3f2; color: #c0392b; }
    .etat-remplace  { background: #fef3e2; color: #d97706; }
    .etat-supprime  { background: #f0f0f0; color: #555; }
    .etat-autre     { background: #f0f0f0; color: #888; }

    /* ── Map error ── */
    .map-error {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        color: #c0392b;
        background: #fdf3f2;
        border: 1px solid #f5c6c2;
        border-radius: 10px;
        padding: 16px 20px;
        margin: 20px;
        line-height: 1.7;
    }

    /* ── Selected tree indicator ── */
    .selected-label {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        font-weight: 500;
        color: rgba(255,255,255,0.75);
        font-style: italic;
        margin-left: auto;
    }

    /* ── CTA bottom ── */
    .visu-cta {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.07);
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }
    .visu-cta p {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        color: #777;
        margin: 0;
    }
    .visu-cta strong {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.95rem;
        color: #333;
        display: block;
        margin-bottom: 4px;
    }
    .btn-cta-green {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 10px 24px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, var(--green-mid), var(--green-soft));
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0,77,0,0.2);
        white-space: nowrap;
    }
    .btn-cta-green:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0,77,0,0.3);
        color: #fff;
    }
    .btn-cta-outline {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.78rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 10px 24px;
        border-radius: 10px;
        border: 1.5px solid #ddd;
        background: #fff;
        color: #555;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-cta-outline:hover { border-color: var(--green-soft); color: var(--green-soft); background: var(--green-light); }
</style>

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
                        <th class="text-center">★</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arbres as $arbre):
                        $etatClass = match($arbre['fk_etat']) {
                            'EN PLACE'      => 'etat-en-place',
                            'ABATTU'        => 'etat-abattu',
                            'REMPLACÉ'      => 'etat-remplace',
                            'SUPPRIMÉ'      => 'etat-supprime',
                            default         => 'etat-autre',
                        };
                    ?>
                        <tr class="arbre-row"
                            data-lat="<?= $arbre['lat'] ?>"
                            data-lon="<?= $arbre['lon'] ?>"
                            data-id="<?= $arbre['id_arbre'] ?>"
                            data-nom="<?= htmlspecialchars($arbre['nom'] ?? '—') ?>">
                            <td><?= $arbre['id_arbre'] ?></td>
                            <td><?= htmlspecialchars($arbre['nom'] ?? '—') ?></td>
                            <td><?= $arbre['created_date'] ?? '—' ?></td>
                            <td><?= htmlspecialchars($arbre['clc_quartier']) ?></td>
                            <td><?= htmlspecialchars($arbre['clc_secteur']) ?></td>
                            <td><?= $arbre['haut_tot'] ?></td>
                            <td><?= $arbre['tronc_diam'] ?></td>
                            <td><span class="etat-badge <?= $etatClass ?>"><?= htmlspecialchars($arbre['fk_etat']) ?></span></td>
                            <td><?= htmlspecialchars($arbre['fk_stade_dev']) ?></td>
                            <td class="text-center">
                                <?php if ($arbre['remarquable']): ?>
                                    <i class="fas fa-star" style="color:#d97706; font-size:0.85rem;"></i>
                                <?php else: ?>
                                    <i class="far fa-star" style="color:#ddd; font-size:0.85rem;"></i>
                                <?php endif; ?>
                            </td>
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