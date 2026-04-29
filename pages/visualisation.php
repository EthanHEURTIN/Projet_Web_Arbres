<?php


// Récupération de la liste des arbres pour le menu déroulant
$arbres = $pdo->query("SELECT id_arbre, nom FROM arbre ORDER BY nom ASC")->fetchAll();
?>

<div class="container my-5">
    <h2 class="text-success fw-bold mb-4">Visualisation Géographique</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="treeSelect" class="form-label fw-bold">Sélectionner un arbre :</label>
            <select class="form-select shadow-sm" id="treeSelect">
                <option selected>Choisir un spécimen dans la liste...</option>
                <?php foreach ($arbres as $arbre): ?>
                    <option value="<?php echo $arbre['id_arbre']; ?>">
                        <?php echo htmlspecialchars($arbre['nom']); ?> (ID: <?php echo $arbre['id_arbre']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-0">
            <iframe src="/scripts_ia/map_plotly.html" width="100%" height="600" frameborder="0"></iframe>
        </div>
    </div>

    <div class="row justify-content-center border-top pt-4">
        <div class="col-md-8 text-center">
            <p class="text-muted mb-3">Analyses prédictives disponibles pour ces données :</p>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a href="prediction_age.php" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="bi bi-calendar3"></i> Prédiction de l'Âge
                </a>
                <a href="prediction_cluster.php" class="btn btn-info btn-lg px-4 text-white shadow">
                    <i class="bi bi-diagram-3"></i> Prédiction Cluster
                </a>
            </div>
        </div>
    </div>
</div>

