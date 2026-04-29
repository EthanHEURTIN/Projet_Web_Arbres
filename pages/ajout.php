<?php
// Récupération des valeurs distinctes pour les listes déroulantes
try {
    $especes   = $pdo->query("SELECT DISTINCT nom FROM arbre WHERE nom IS NOT NULL ORDER BY nom")->fetchAll(PDO::FETCH_COLUMN);
    $quartiers = $pdo->query("SELECT DISTINCT clc_quartier FROM arbre ORDER BY clc_quartier")->fetchAll(PDO::FETCH_COLUMN);
    $secteurs  = $pdo->query("SELECT DISTINCT clc_secteur FROM arbre ORDER BY clc_secteur")->fetchAll(PDO::FETCH_COLUMN);
    $ports     = $pdo->query("SELECT DISTINCT fk_port FROM arbre WHERE fk_port IS NOT NULL ORDER BY fk_port")->fetchAll(PDO::FETCH_COLUMN);
    $pieds     = $pdo->query("SELECT DISTINCT fk_pied FROM arbre WHERE fk_pied IS NOT NULL ORDER BY fk_pied")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération & nettoyage
    $nom         = trim($_POST['nom']         ?? '');
    $haut_tot    = $_POST['haut_tot']    ?? '';
    $haut_tronc  = $_POST['haut_tronc']  ?? '';
    $tronc_diam  = $_POST['tronc_diam']  ?? '';
    $lat         = $_POST['lat']         ?? '';
    $lon         = $_POST['lon']         ?? '';
    $fk_etat     = $_POST['fk_etat']     ?? '';
    $fk_stade    = $_POST['fk_stade_dev'] ?? '';
    $fk_port     = $_POST['fk_port']     ?? '';
    $fk_pied     = $_POST['fk_pied']     ?? '';
    $fk_situation= $_POST['fk_situation'] ?? '';
    $feuillage   = $_POST['feuillage']   ?? '';
    $villeca     = $_POST['villeca']     ?? '';
    $remarquable = isset($_POST['remarquable']) ? 1 : 0;
    $commentaire = trim($_POST['commentaire_environnement'] ?? '');
    $quartier    = trim($_POST['clc_quartier'] ?? '');
    $secteur     = trim($_POST['clc_secteur']  ?? '');
    $created_date = date('Y-m-d');

    // Validations basiques
    if ($haut_tot   === '' || !is_numeric($haut_tot))   $errors[] = "Hauteur totale invalide.";
    if ($haut_tronc === '' || !is_numeric($haut_tronc)) $errors[] = "Hauteur du tronc invalide.";
    if ($tronc_diam === '' || !is_numeric($tronc_diam)) $errors[] = "Diamètre du tronc invalide.";
    if ($lat        === '' || !is_numeric($lat))         $errors[] = "Latitude invalide.";
    if ($lon        === '' || !is_numeric($lon))         $errors[] = "Longitude invalide.";
    if ($fk_etat    === '')  $errors[] = "L'état de l'arbre est requis.";
    if ($fk_stade   === '')  $errors[] = "Le stade de développement est requis.";
    if ($quartier   === '')  $errors[] = "Le quartier est requis.";
    if ($secteur    === '')  $errors[] = "Le secteur est requis.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO arbre (
                    created_date, nom, haut_tot, haut_tronc, tronc_diam,
                    lat, lon, fk_etat, fk_stade_dev, fk_port, fk_pied,
                    fk_situation, feuillage, villeca, remarquable,
                    commentaire_environnement, clc_quartier, clc_secteur
                ) VALUES (
                    :created_date, :nom, :haut_tot, :haut_tronc, :tronc_diam,
                    :lat, :lon, :fk_etat, :fk_stade_dev, :fk_port, :fk_pied,
                    :fk_situation, :feuillage, :villeca, :remarquable,
                    :commentaire_environnement, :clc_quartier, :clc_secteur
                )
            ");
            $stmt->execute([
                ':created_date'               => $created_date,
                ':nom'                        => $nom ?: null,
                ':haut_tot'                   => floatval($haut_tot),
                ':haut_tronc'                 => floatval($haut_tronc),
                ':tronc_diam'                 => floatval($tronc_diam),
                ':lat'                        => floatval($lat),
                ':lon'                        => floatval($lon),
                ':fk_etat'                    => $fk_etat,
                ':fk_stade_dev'               => $fk_stade,
                ':fk_port'                    => $fk_port ?: null,
                ':fk_pied'                    => $fk_pied ?: null,
                ':fk_situation'               => $fk_situation ?: null,
                ':feuillage'                  => $feuillage ?: null,
                ':villeca'                    => $villeca ?: null,
                ':remarquable'                => $remarquable,
                ':commentaire_environnement'  => $commentaire ?: null,
                ':clc_quartier'               => $quartier,
                ':clc_secteur'                => $secteur,
            ]);
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Erreur base de données : " . $e->getMessage();
        }
    }
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
    .ajout-hero {
        background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-dark) 100%);
        padding: 48px 0 36px;
        margin-bottom: 40px;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .ajout-hero::before {
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
    .ajout-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        color: #fff;
        letter-spacing: 1px;
        margin: 0;
    }
    .ajout-hero p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: rgba(255,255,255,0.55);
        margin: 6px 0 0;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .ajout-hero .hero-icon {
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
    .ajout-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 24px;
        height: 100%;
    }
    .ajout-card-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .ajout-card-header i { color: var(--green-accent); font-size: 0.95rem; }
    .ajout-card-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .ajout-card-body { padding: 24px 28px; }

    /* ── Fields ── */
    .ajout-field { margin-bottom: 18px; }
    .ajout-field label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #555;
        margin-bottom: 6px;
        display: block;
    }
    .ajout-field label i {
        color: var(--green-soft);
        margin-right: 6px;
        width: 14px;
        text-align: center;
    }
    .ajout-field .form-control,
    .ajout-field .form-select {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.88rem;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: #fafafa;
        color: #333;
    }
    .ajout-field .form-control:focus,
    .ajout-field .form-select:focus {
        border-color: var(--green-soft);
        box-shadow: 0 0 0 3px rgba(0,100,0,0.1);
        background-color: #fff;
        outline: none;
    }
    .ajout-field textarea.form-control { resize: vertical; min-height: 90px; }

    /* Checkbox remarquable */
    .remarquable-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fafafa;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 16px;
        cursor: pointer;
        transition: border-color 0.2s;
        user-select: none;
    }
    .remarquable-wrap:hover { border-color: var(--green-soft); }
    .remarquable-wrap input[type="checkbox"] {
        width: 18px; height: 18px;
        accent-color: var(--green-soft);
        cursor: pointer;
        flex-shrink: 0;
    }
    .remarquable-wrap span {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.88rem;
        font-weight: 500;
        color: #444;
    }
    .remarquable-wrap .rq-badge {
        margin-left: auto;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        background: var(--green-light);
        color: var(--green-soft);
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* ── Alerts ── */
    .ajout-alert-error {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        background: #fdf3f2;
        border: 1px solid #f5c6c2;
        border-radius: 12px;
        padding: 16px 20px;
        color: #c0392b;
        margin-bottom: 24px;
        line-height: 1.8;
    }
    .ajout-alert-error i { margin-right: 6px; }

    .ajout-alert-success {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.88rem;
        background: var(--green-light);
        border: 1px solid #a8d5a8;
        border-radius: 12px;
        padding: 18px 24px;
        color: var(--green-soft);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .ajout-alert-success i { font-size: 1.2rem; }

    /* ── Buttons ── */
    .ajout-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 8px;
        padding-bottom: 40px;
    }
    .btn-ajout-back {
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
    .btn-ajout-back:hover { border-color: #bbb; background: #f5f5f5; color: #444; }

    .btn-ajout-submit {
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
    .btn-ajout-submit:hover {
        box-shadow: 0 6px 20px rgba(0,77,0,0.35);
        transform: translateY(-1px);
        color: #fff;
    }

    /* Section title divider */
    .section-label {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--green-soft);
        border-bottom: 1px solid var(--green-light);
        padding-bottom: 6px;
        margin-bottom: 16px;
    }
</style>

<!-- Hero -->
<div class="container mt-4">
    <div class="ajout-hero">
        <div class="px-4">
            <div class="d-flex align-items-center">
                <div class="hero-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div>
                    <h1><i class="fas fa-tree me-2" style="color:var(--green-accent);font-size:1.5rem;"></i>Ajout d'un arbre</h1>
                    <p>Enregistrez un nouvel arbre dans la base de données du patrimoine arboré</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    <?php if ($success): ?>
        <div class="ajout-alert-success">
            <i class="fas fa-circle-check"></i>
            L'arbre a été ajouté avec succès dans la base de données.
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="ajout-alert-error">
            <i class="fas fa-exclamation-triangle"></i><strong>Veuillez corriger les erreurs suivantes :</strong><br>
            <?php foreach ($errors as $err): ?>
                — <?= htmlspecialchars($err) ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="row g-4">

            <!-- Colonne 1 : Identité & mesures -->
            <div class="col-lg-6">
                <div class="ajout-card">
                    <div class="ajout-card-header">
                        <i class="fas fa-ruler-combined"></i>
                        <span>Identité & mesures</span>
                    </div>
                    <div class="ajout-card-body">

                        <div class="section-label">Espèce</div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-leaf"></i>Espèce</label>
                                    <select class="form-select" name="nom">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach ($especes as $e): ?>
                                            <option value="<?= htmlspecialchars($e) ?>"
                                                <?= (($_POST['nom'] ?? '') === $e) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($e) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-spa"></i>Feuillage</label>
                                    <select class="form-select" name="feuillage">
                                        <option value="">— Sélectionner —</option>
                                        <option value="Feuillu"   <?= (($_POST['feuillage'] ?? '') === 'Feuillu')   ? 'selected' : '' ?>>Feuillu</option>
                                        <option value="Conifère"  <?= (($_POST['feuillage'] ?? '') === 'Conifère')  ? 'selected' : '' ?>>Conifère</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="section-label">Mesures dendrométriques</div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-4">
                                <div class="ajout-field">
                                    <label><i class="fas fa-arrows-alt-v"></i>Haut. totale</label>
                                    <input type="number" step="0.01" class="form-control" name="haut_tot"
                                           placeholder="ex : 12.5" value="<?= htmlspecialchars($_POST['haut_tot'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="ajout-field">
                                    <label><i class="fas fa-ruler-vertical"></i>Haut. tronc</label>
                                    <input type="number" step="0.01" class="form-control" name="haut_tronc"
                                           placeholder="ex : 2.0" value="<?= htmlspecialchars($_POST['haut_tronc'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="ajout-field">
                                    <label><i class="fas fa-circle-notch"></i>Ø tronc</label>
                                    <input type="number" step="0.01" class="form-control" name="tronc_diam"
                                           placeholder="ex : 0.35" value="<?= htmlspecialchars($_POST['tronc_diam'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="section-label">Localisation</div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-map-marker-alt"></i>Latitude</label>
                                    <input type="number" step="0.000001" class="form-control" name="lat"
                                           placeholder="ex : 49.8490" value="<?= htmlspecialchars($_POST['lat'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-map-pin"></i>Longitude</label>
                                    <input type="number" step="0.000001" class="form-control" name="lon"
                                           placeholder="ex : 3.2874" value="<?= htmlspecialchars($_POST['lon'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-city"></i>Quartier</label>
                                    <select class="form-select" name="clc_quartier">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach ($quartiers as $q): ?>
                                            <option value="<?= htmlspecialchars($q) ?>"
                                                <?= (($_POST['clc_quartier'] ?? '') === $q) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($q) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-map"></i>Secteur</label>
                                    <select class="form-select" name="clc_secteur">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach ($secteurs as $sec): ?>
                                            <option value="<?= htmlspecialchars($sec) ?>"
                                                <?= (($_POST['clc_secteur'] ?? '') === $sec) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($sec) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Colonne 2 : Caractéristiques & état -->
            <div class="col-lg-6">
                <div class="ajout-card">
                    <div class="ajout-card-header">
                        <i class="fas fa-tags"></i>
                        <span>Caractéristiques & état</span>
                    </div>
                    <div class="ajout-card-body">

                        <div class="section-label">État & développement</div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-heartbeat"></i>État de l'arbre</label>
                                    <select class="form-select" name="fk_etat">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach (['ABATTU','EN PLACE','Essouché','Non essouché','REMPLACÉ','SUPPRIMÉ'] as $etat): ?>
                                            <option value="<?= $etat ?>" <?= (($_POST['fk_etat'] ?? '') === $etat) ? 'selected' : '' ?>>
                                                <?= $etat ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-chart-line"></i>Stade de développement</label>
                                    <select class="form-select" name="fk_stade_dev">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach (['jeune','adulte','vieux','senescent'] as $stade): ?>
                                            <option value="<?= $stade ?>" <?= (($_POST['fk_stade_dev'] ?? '') === $stade) ? 'selected' : '' ?>>
                                                <?= ucfirst($stade) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="section-label">Morphologie</div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-tree"></i>Type de port</label>
                                    <select class="form-select" name="fk_port">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach ($ports as $port): ?>
                                            <option value="<?= htmlspecialchars($port) ?>"
                                                <?= (($_POST['fk_port'] ?? '') === $port) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($port) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-seedling"></i>Type de pied</label>
                                    <select class="form-select" name="fk_pied">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach ($pieds as $pied): ?>
                                            <option value="<?= htmlspecialchars($pied) ?>"
                                                <?= (($_POST['fk_pied'] ?? '') === $pied) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($pied) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-object-group"></i>Situation</label>
                                    <select class="form-select" name="fk_situation">
                                        <option value="">— Sélectionner —</option>
                                        <?php foreach (['Alignement','Groupe','Isolé'] as $sit): ?>
                                            <option value="<?= $sit ?>" <?= (($_POST['fk_situation'] ?? '') === $sit) ? 'selected' : '' ?>>
                                                <?= $sit ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="ajout-field">
                                    <label><i class="fas fa-building"></i>Ville / CASQ</label>
                                    <select class="form-select" name="villeca">
                                        <option value="">— Sélectionner —</option>
                                        <option value="VILLE" <?= (($_POST['villeca'] ?? '') === 'VILLE') ? 'selected' : '' ?>>VILLE</option>
                                        <option value="CASQ"  <?= (($_POST['villeca'] ?? '') === 'CASQ')  ? 'selected' : '' ?>>CASQ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="section-label">Informations complémentaires</div>
                        <div class="ajout-field">
                            <label><i class="fas fa-comment-alt"></i>Commentaire environnement</label>
                            <textarea class="form-control" name="commentaire_environnement"
                                      placeholder="Observations, contexte environnemental..."><?= htmlspecialchars($_POST['commentaire_environnement'] ?? '') ?></textarea>
                        </div>

                        <div class="ajout-field">
                            <label for="remarquable"><i class="fas fa-star"></i>Arbre remarquable</label>
                            <label class="remarquable-wrap" for="remarquable">
                                <input type="checkbox" name="remarquable" id="remarquable"
                                       <?= isset($_POST['remarquable']) ? 'checked' : '' ?>>
                                <span>Cocher si cet arbre est classé remarquable</span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Actions -->
        <div class="ajout-actions">
            <a href="javascript:history.back()" class="btn-ajout-back">
                <i class="fas fa-arrow-left"></i>Retour
            </a>
            <button type="submit" class="btn-ajout-submit">
                <i class="fas fa-plus"></i>Ajouter l'arbre
            </button>
        </div>

    </form>
</div>