<?php
/**
 * footer.php — Pied de page commun à toutes les pages
 * Projet Patrimoine Arboré — ISEN Nantes FISA4 — 2024/2025
 */
?>

<footer class="site-footer">
    <div class="footer-inner">

        <!-- Logo -->
        <div class="footer-logo">
            <img src="assets/img/logo.png" alt="Logo Patrimoine Arboré" />
        </div>

        <!-- Infos projet -->
        <div class="footer-info">
            <p class="footer-project">Patrimoine Arboré &mdash; 2024/2025</p>
            <p class="footer-school">ISEN Nantes &middot; Projet FISA4 Big Data / IA / Web</p>
        </div>

        <!-- Membres -->
        <div class="footer-team">
            <p class="footer-team-label">Équipe</p>
            <ul>
                <li>Mélissa</li>
                <li>Ethan</li>
                <li>Pierre</li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> &mdash; Mairie de Saint-Quentin (Aisne)</p>
    </div>
</footer>

<style>
/* =============================================
   FOOTER — Patrimoine Arboré
   Charte : vert foncé #2d5a27, fond sombre,
   typographie sobre et professionnelle
   ============================================= */

.site-footer {
    background-color: #1e3d1a;
    color: #c8dfc5;
    font-family: 'Segoe UI', Calibri, sans-serif;
    font-size: 0.88rem;
    margin-top: auto;
    border-top: 3px solid #2d5a27;
}

.footer-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
    padding: 1.6rem 2rem;
}

/* Logo */
.footer-logo img {
    height: 56px;
    width: auto;
    opacity: 0.92;
    filter: brightness(1.1);
}

/* Infos projet */
.footer-info {
    flex: 1;
    text-align: center;
}

.footer-project {
    font-size: 1rem;
    font-weight: 600;
    color: #e0f0dc;
    margin: 0 0 0.25rem 0;
    letter-spacing: 0.02em;
}

.footer-school {
    margin: 0;
    color: #9dba99;
    font-size: 0.82rem;
}

/* Membres -->
.footer-team {
    text-align: right;
}

.footer-team-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #7aaa74;
    margin: 0 0 0.4rem 0;
    font-weight: 600;
}

.footer-team ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-team ul li {
    color: #c8dfc5;
    line-height: 1.6;
}

/* Barre basse */
.footer-bottom {
    background-color: #162e13;
    text-align: center;
    padding: 0.55rem 1rem;
    font-size: 0.75rem;
    color: #6a9666;
    border-top: 1px solid #2a4f26;
}

.footer-bottom p {
    margin: 0;
}

/* Responsive */
@media (max-width: 640px) {
    .footer-inner {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .footer-team {
        text-align: center;
    }
}
</style>
