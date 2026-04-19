<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Box</h1>
    <p class="text-muted mb-4 text-center">Configurez les informations de votre box Internet à votre guise.</p>

    <div class="row g-4">

        <?php 

            // Require instead of include because if a template file is missing, the page should not continue.
            // If one of these is missing, it's better that the page breaks immediately.
            // Always use __DIR__ to define path relative to your current file.
            require __DIR__ . "/templates/formulaire_ip.php";
            require __DIR__ . "/templates/nom_de_domaine.php";

        ?>

    </div>

</main>
