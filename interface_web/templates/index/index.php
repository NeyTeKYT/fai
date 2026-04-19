<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Tableau de bord</h1>
    <p class="text-muted mb-4 text-center">Visualisation des informations générales sur la box Internet.</p>

    <div class="row g-4">

        <?php 

            // Require instead of include because if a template file is missing, the page should not continue.
            // If one of these is missing, it's better that the page breaks immediately.
            // Always use __DIR__ to define path relative to your current file.
            require __DIR__ . "/templates/infos_generales.php";
            require __DIR__ . "/templates/serveurs_status.php";
            require __DIR__ . "/templates/configuration_ip_masque.php";
            require __DIR__ . "/templates/configuration_nom_domaine_box.php";
            require __DIR__ . "/templates/plage_dhcp.php";
            require __DIR__ . "/templates/appareils_connectes.php";
            require __DIR__ . "/templates/security_state.php";
            require __DIR__ . "/templates/lien_vers_messagerie.php";

        ?>

    </div>

</main>
