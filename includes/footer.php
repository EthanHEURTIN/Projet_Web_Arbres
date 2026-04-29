<style>
    .site-footer {
        background: linear-gradient(135deg, #004d00 0%, #002b00 100%);
        margin-top: 60px;
        padding: 48px 0 0;
        position: relative;
        overflow: hidden;
    }
    .site-footer::before {
        content: '\f1bb';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: -40px; bottom: -30px;
        font-size: 220px;
        color: rgba(255,255,255,0.03);
        pointer-events: none;
        line-height: 1;
    }

    .footer-brand .brand-logo {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        color: #8dbb8d;
        margin-bottom: 14px;
    }
    .footer-brand .brand-name {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: #fff;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .footer-brand .brand-desc {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.5);
        line-height: 1.7;
        margin: 0;
    }

    .footer-col-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #8dbb8d;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .footer-col-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,0.08);
    }

    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 500;
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s, gap 0.2s;
    }
    .footer-links a i {
        color: #8dbb8d;
        font-size: 0.75rem;
        width: 14px;
        text-align: center;
        transition: transform 0.2s;
    }
    .footer-links a:hover { color: #fff; gap: 12px; }
    .footer-links a:hover i { transform: translateX(2px); }

    /* Membres non-cliquables */
    .footer-members { list-style: none; padding: 0; margin: 0; }
    .footer-members li {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 500;
        color: rgba(255,255,255,0.6);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    .footer-members li .member-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: rgba(141,187,141,0.15);
        border: 1px solid rgba(141,187,141,0.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
        color: #8dbb8d;
        flex-shrink: 0;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
    }

    .footer-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.07);
        margin: 36px 0 0;
    }
    .footer-bottom { padding: 16px 0; }
    .footer-bottom .copy {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        color: rgba(255,255,255,0.35);
        margin: 0;
    }
    .footer-bottom .copy span { color: #8dbb8d; }
    .footer-bottom .made-by {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        color: rgba(255,255,255,0.35);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .footer-bottom .made-by i { color: #e53e3e; font-size: 0.7rem; }
</style>

<footer class="site-footer">
    <div class="container">
        <div class="row g-5">

            <!-- Brand -->
            <div class="col-lg-4 footer-brand">
                <div class="brand-logo"><i class="fas fa-tree"></i></div>
                <div class="brand-name">Patrimoine Arboré<br>Saint-Quentin</div>
                <p class="brand-desc">
                    Plateforme de gestion et d'analyse prédictive du patrimoine arboré urbain,
                    développée pour la ville de Saint-Quentin.
                </p>
            </div>

            <!-- Navigation -->
            <div class="col-sm-6 col-lg-4">
                <div class="footer-col-title">Navigation</div>
                <ul class="footer-links">
                    <li><a href="accueil"><i class="fas fa-home"></i>Accueil</a></li>
                    <li><a href="visualisation"><i class="fas fa-map-marked-alt"></i>Visualisation</a></li>
                    <li><a href="ajout"><i class="fas fa-plus-circle"></i>Ajouter un arbre</a></li>
                    <li><a href="predire_age"><i class="fas fa-brain"></i>Prédiction de l'âge</a></li>
                    <li><a href="predire_cluster"><i class="fas fa-layer-group"></i>Prédiction cluster</a></li>
                    <li><a href="admin"><i class="fas fa-user-shield"></i>Administration</a></li>
                </ul>
            </div>

            <!-- Équipe -->
            <div class="col-sm-6 col-lg-4">
                <div class="footer-col-title">Équipe projet</div>
                <ul class="footer-members">
                    <li>
                        <div class="member-avatar">MB</div>
                        Mélissa BOUANCHAUD
                    </li>
                    <li>
                        <div class="member-avatar">PS</div>
                        Pierre SICOT
                    </li>
                    <li>
                        <div class="member-avatar">EH</div>
                        Ethan HEURTIN
                    </li>
                </ul>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
            <p class="copy">
                © <?= date('Y') ?> <span>Ville de Saint-Quentin</span> — Tous droits réservés
            </p>
            <p class="made-by">
                Fait avec <i class="fas fa-heart"></i> dans le cadre d'un projet Big Data & IA
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>