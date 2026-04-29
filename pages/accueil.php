<?php
try {
    $total         = $pdo->query("SELECT COUNT(*) FROM arbre")->fetchColumn();
    $tailleMoyenne = round($pdo->query("SELECT AVG(haut_tot) FROM arbre")->fetchColumn(), 2);
    $ageMoyen      = round($pdo->query("SELECT AVG(age_estim) FROM arbre")->fetchColumn(), 1);
    $risque        = $pdo->query("SELECT COUNT(*) FROM arbre WHERE fk_stade_dev IN ('vieux', 'senescent')")->fetchColumn();
    $remarquables  = $pdo->query("SELECT COUNT(*) FROM arbre WHERE remarquable = 1")->fetchColumn();
} catch (Exception $e) {
    $total = $tailleMoyenne = $ageMoyen = $risque = $remarquables = "N/A";
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
    .home-hero {
        background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-dark) 100%);
        border-radius: 20px;
        padding: 60px 48px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    .home-hero::before {
        content: '\f1bb';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -40px; bottom: -40px;
        font-size: 280px;
        color: rgba(255,255,255,0.04);
        pointer-events: none;
        line-height: 1;
    }
    .home-hero::after {
        content: '';
        position: absolute;
        top: -60px; left: -60px;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: rgba(141,187,141,0.06);
        pointer-events: none;
    }
    .home-hero .hero-eyebrow {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--green-accent);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .home-hero .hero-eyebrow::before {
        content: '';
        display: inline-block;
        width: 28px; height: 2px;
        background: var(--green-accent);
        border-radius: 2px;
    }
    .home-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.6rem;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 16px;
    }
    .home-hero h1 span { color: var(--green-accent); }
    .home-hero p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 1rem;
        color: rgba(255,255,255,0.65);
        margin-bottom: 32px;
        max-width: 520px;
        line-height: 1.7;
    }
    .btn-hero-primary {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 13px 32px;
        border-radius: 10px;
        border: none;
        background: #fff;
        color: var(--green-mid);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .btn-hero-primary:hover {
        background: var(--green-accent);
        color: var(--green-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    .btn-hero-secondary {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.82rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 13px 28px;
        border-radius: 10px;
        border: 1.5px solid rgba(255,255,255,0.3);
        background: transparent;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s;
    }
    .btn-hero-secondary:hover {
        border-color: rgba(255,255,255,0.7);
        background: rgba(255,255,255,0.08);
        color: #fff;
    }

    /* ── Stat cards ── */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
        padding: 28px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 40px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-icon.green  { background: var(--green-light); color: var(--green-soft); }
    .stat-icon.blue   { background: #e8f0fb; color: #2563eb; }
    .stat-icon.amber  { background: #fef3e2; color: #d97706; }
    .stat-icon.purple { background: #f3f0fe; color: #7c3aed; }
    .stat-value {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.9rem;
        color: #1a1a1a;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
    }

    /* ── Alert danger ── */
    .danger-banner {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.07);
        border-left: 5px solid #e53e3e;
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .danger-banner .danger-icon {
        width: 48px; height: 48px;
        background: #fff5f5;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        color: #e53e3e;
        flex-shrink: 0;
    }
    .danger-banner .danger-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        color: #c53030;
        margin-bottom: 4px;
    }
    .danger-banner .danger-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        color: #666;
        margin: 0;
    }
    .danger-banner .danger-text strong { color: #c53030; }
    .btn-danger-action {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 10px 24px;
        border-radius: 10px;
        border: none;
        background: #e53e3e;
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-danger-action:hover {
        background: #c53030;
        color: #fff;
        transform: translateY(-1px);
    }

    /* ── Section cards (À propos / IA) ── */
    .info-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
        height: 100%;
    }
    .info-card-header {
        background: linear-gradient(90deg, var(--green-mid), var(--green-soft));
        padding: 14px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .info-card-header i { color: var(--green-accent); font-size: 0.95rem; }
    .info-card-header span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .info-card-body { padding: 28px; }
    .info-card-body p {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.88rem;
        color: #555;
        line-height: 1.75;
        margin-bottom: 16px;
    }

    /* Feature list */
    .feature-list { list-style: none; padding: 0; margin: 0; }
    .feature-list li {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.86rem;
        color: #444;
        padding: 10px 14px;
        border-radius: 10px;
        margin-bottom: 8px;
        background: #fafafa;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        line-height: 1.5;
    }
    .feature-list li i {
        color: var(--green-soft);
        margin-top: 2px;
        flex-shrink: 0;
    }
    .feature-list li strong { color: var(--green-mid); }

    /* Logo illustration */
    .home-logo-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 20px;
    }
    .home-logo-wrap img {
        max-height: 260px;
        width: auto;
        border-radius: 50%;
        box-shadow: 0 12px 40px rgba(0,77,0,0.15);
        background: var(--green-light);
        padding: 20px;
    }

    /* Section title */
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--green-soft);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--green-light);
    }

    /* CTA actions bottom card */
    .cta-card {
        background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
        border-radius: 16px;
        padding: 36px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }
    .cta-card::before {
        content: '\f055';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 20px; top: -10px;
        font-size: 140px;
        color: rgba(255,255,255,0.04);
        pointer-events: none;
    }
    .cta-card h3 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        color: #fff;
        margin-bottom: 6px;
    }
    .cta-card p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 0.88rem;
        color: rgba(255,255,255,0.6);
        margin: 0;
    }
</style>

<div class="container mt-4 pb-5">

    <!-- ── Hero ── -->
    <div class="home-hero mb-4">
        <div class="hero-eyebrow">Patrimoine Arboré — Saint-Quentin</div>
        <h1>Gérez vos arbres avec<br><span>l'Intelligence Artificielle</span></h1>
        <p>Analyse prédictive, clustering et suivi en temps réel de plus de 12 000 arbres pour une gestion durable des espaces verts.</p>
        <div class="d-flex flex-wrap gap-3">
            <a href="index.php?url=visualisation" class="btn-hero-primary">
                <i class="fas fa-map-marked-alt"></i>Voir la carte interactive
            </a>
            <a href="index.php?url=ajout" class="btn-hero-secondary">
                <i class="fas fa-plus-circle"></i>Ajouter un arbre
            </a>
        </div>
    </div>

    <!-- ── Statistiques ── -->
    <div class="section-title">Statistiques du parc arboré</div>
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-tree"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($total, 0, ',', ' ') ?></div>
                    <div class="stat-label">Arbres répertoriés</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-ruler-vertical"></i></div>
                <div>
                    <div class="stat-value"><?= $tailleMoyenne ?> m</div>
                    <div class="stat-label">Hauteur moyenne</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-hourglass-half"></i></div>
                <div>
                    <div class="stat-value"><?= $ageMoyen ?> ans</div>
                    <div class="stat-label">Âge moyen estimé</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-star"></i></div>
                <div>
                    <div class="stat-value"><?= $remarquables ?></div>
                    <div class="stat-label">Arbres remarquables</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Alerte sécurité ── -->
    <div class="danger-banner mb-4">
        <div class="d-flex align-items-center gap-16 flex-wrap gap-3">
            <div class="danger-icon"><i class="fas fa-triangle-exclamation"></i></div>
            <div>
                <div class="danger-title">Diagnostic de sécurité</div>
                <p class="danger-text">
                    <strong><?= $risque ?> arbres</strong> identifiés comme vieux ou sénescents nécessitent une surveillance renforcée.
                </p>
            </div>
        </div>
        <a href="index.php?url=visualisation&filter=danger" class="btn-danger-action">
            <i class="fas fa-search-location"></i>Identifier les zones
        </a>
    </div>

    <!-- ── À propos + IA ── -->
    <div class="section-title">À propos du projet</div>
    <div class="row g-4 mb-4">

        <div class="col-lg-7">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i>
                    <span>Ville de Saint-Quentin</span>
                </div>
                <div class="info-card-body">
                    <p>
                        Ce projet est une Preuve de Concept développée pour la ville de Saint-Quentin, visant à moderniser la gestion de son patrimoine arboré grâce à la data et à l'intelligence artificielle.
                    </p>
                    <p>
                        En combinant analyse Big Data et algorithmes de Machine Learning, l'application traite plus de 12 000 arbres pour trois finalités :
                    </p>
                    <ul class="feature-list">
                        <li>
                            <i class="fas fa-layer-group"></i>
                            <div><strong>Clustering :</strong> Classifier automatiquement les arbres par profil morphologique et localisation.</div>
                        </li>
                        <li>
                            <i class="fas fa-brain"></i>
                            <div><strong>Prédiction :</strong> Estimer l'âge des spécimens à partir de leurs mesures physiques grâce à un modèle entraîné.</div>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <div><strong>Sécurité :</strong> Identifier préventivement les arbres présentant un risque structurel en cas de tempête.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-seedling"></i>
                    <span>Notre approche IA</span>
                </div>
                <div class="info-card-body">
                    <div class="home-logo-wrap">
                        <img src="assets/img/logo_arborinfo.png" alt="Logo Arbor'Info">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── CTA bas de page ── -->
    <div class="cta-card">
        <div>
            <h3>Prêt à explorer le patrimoine ?</h3>
            <p>Lancez une prédiction d'âge ou consultez la carte des arbres de Saint-Quentin.</p>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <a href="index.php?url=predire_age" class="btn-hero-primary">
                <i class="fas fa-brain"></i>Prédire un âge
            </a>
            <a href="index.php?url=visualisation" class="btn-hero-secondary">
                <i class="fas fa-map"></i>Carte interactive
            </a>
        </div>
    </div>

</div>