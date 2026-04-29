<?php
try {
    $total         = $pdo->query("SELECT COUNT(*) FROM arbre")->fetchColumn();
    $tailleMoyenne = round($pdo->query("SELECT AVG(haut_tot) FROM arbre")->fetchColumn(), 2);
    $ageMoyen      = round($pdo->query("SELECT AVG(age_estim) FROM arbre")->fetchColumn(), 1);
    $risque        = $pdo->query("SELECT COUNT(*) FROM arbre WHERE fk_stade_dev IN ('vieux', 'senescent')")->fetchColumn();
    $remarquables  = $pdo->query("SELECT COUNT(*) FROM arbre WHERE remarquable = 'Oui'")->fetchColumn();
} catch (Exception $e) {
    $total = $tailleMoyenne = $ageMoyen = $risque = $remarquables = "N/A";
}
?>

<link rel="stylesheet" href="/style/style_accueil.css">

<div class="container mt-4 pb-5">

    <!-- ── Hero ── -->
    <div class="home-hero mb-4">
        <div class="hero-eyebrow">Patrimoine Arboré — Saint-Quentin</div>
        <h1>Gérez vos arbres avec<br><span>l'Intelligence Artificielle</span></h1>
        <p>Analyse prédictive, clustering et suivi en temps réel de plus de 12 000 arbres pour une gestion durable des espaces verts.</p>
        <div class="d-flex flex-wrap gap-3">
            <a href="visualisation" class="btn-hero-primary">
                <i class="fas fa-map-marked-alt"></i>Voir la carte interactive
            </a>
            <a href="ajout" class="btn-hero-secondary">
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
                    <span>Notre Logo</span>
                </div>
                <div class="info-card-body">
                    <div class="home-logo-wrap">
                        <img src="assets/img/logo.png" alt="Logo">
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