<?php


try {
    // 1. Nombre total d'arbres
    $total = $pdo->query("SELECT COUNT(*) FROM arbre")->fetchColumn();

    // 2. Taille moyenne (haut_tot)
    $tailleMoyenne = $pdo->query("SELECT AVG(haut_tot) FROM arbre")->fetchColumn();
    $tailleMoyenne = round($tailleMoyenne, 2);

    // 3. Âge moyen (age_estim)
    $ageMoyen = $pdo->query("SELECT AVG(age_estim) FROM arbre")->fetchColumn();
    $ageMoyen = round($ageMoyen, 1);

    // 4. Bonus : Arbres à risque (si tu as une colonne risque ou si tu veux filtrer par état)
    // Ici on compte par exemple les arbres "vieux" ou "senescent" comme indicateur de risque
    $risque = $pdo->query("SELECT COUNT(*) FROM arbre WHERE fk_stade_dev IN ('vieux', 'senescent')")->fetchColumn();

} catch (Exception $e) {
    $total = $tailleMoyenne = $ageMoyen = $risque = "N/A";
}


?>

<div class="hero-section bg-light py-5 border-bottom">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-success">Patrimoine Arboré de Saint-Quentin</h1>
        <p class="lead text-muted">Analyse prédictive et gestion durable des espaces verts via l'IA.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="visualisation.php" class="btn btn-success btn-lg px-4 shadow">Voir la carte interactive</a>
            <a href="ajout.php" class="btn btn-outline-secondary btn-lg px-4">Ajouter un arbre</a>
        </div>
    </div>
</div>

<div class="row align-items-center">
        <div class="col-lg-6">
            <h2 class="fw-bold text-success mb-3">À propos d'Arbor'Info</h2>
            <p class="lead">
                Ce projet est une Preuve de Concept développée pour la ville de Saint-Quentin, visant à moderniser la gestion de son patrimoine arboré.
            </p>
            <p>
                En combinant une étude Big Data et l'entrainement d'algorithme d'Intelligence Artificielle notre application permet d'analyser plus de 12 000 arbres pour :
            </p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Clustring :</strong> Classifier les arbres par taille.</li>
                <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Prédiction :</strong> Estimer l'âge des spécimens à partir de leurs mesures physiques.</li>
                <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Sécurité :</strong> Identifier préventivement les arbres présentant un risque en cas de tempête.</li>
            </ul>
        </div>
        <div class="col-lg-6 text-center">
            <img src="logo_arborinfo.png" alt="Illustration Projet" class="img-fluid rounded-circle shadow-sm" style="max-height: 300px; background-color: #f8f9fa; padding: 20px;">
        </div>
    </div>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">🌳</div>
                    <h2 class="display-5 fw-bold text-primary"><?php echo $total; ?></h2>
                    <p class="card-text text-uppercase text-muted fw-bold">Arbres répertoriés</p>
                    <a href="visualisation.php" class="btn btn-outline-primary btn-sm">Explorer les données</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">📏</div>
                    <h2 class="display-5 fw-bold text-success"><?php echo $tailleMoyenne; ?> m</h2>
                    <p class="card-text text-uppercase text-muted fw-bold">Taille moyenne</p>
                    <span class="badge bg-light text-dark border">Indicateur de croissance</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">⏳</div>
                    <h2 class="display-5 fw-bold text-warning"><?php echo $ageMoyen; ?> ans</h2>
                    <p class="card-text text-uppercase text-muted fw-bold">Âge moyen estimé</p>
                    <span class="badge bg-light text-dark border">Maturité du parc</span>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-start border-danger border-5">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-danger">⚠️ Diagnostic de sécurité</h5>
                        <p class="mb-0 text-muted">Il y a actuellement <strong><?php echo $risque; ?></strong> arbres identifiés comme vieux ou sénescents nécessitant une surveillance.</p>
                    </div>
                    <a href="visualisation.php?filter=danger" class="btn btn-danger px-4">Identifier les zones</a>
                </div>
            </div>
        </div>
    </div>
</div>

