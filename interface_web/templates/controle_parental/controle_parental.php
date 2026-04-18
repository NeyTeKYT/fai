<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Contrôle parental</h1>
    <p class="text-muted mb-4 text-center">Bloquez l'accès à Internet à vos enfants.</p>

    <!-- Affichage du message de succès ou d'erreur -->
    <?php if(isset($_SESSION['message'])) : ?>
        <div class="col-12">
            <?= $_SESSION['message'] ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php 

        // Require instead of include because if a template file is missing, the page should not continue.
        // If one of these is missing, it's better that the page breaks immediately.
        // Always use __DIR__ to define path relative to your current file.
        require __DIR__ . "/templates/profil.php";
        require __DIR__ . "/templates/grid.php";

    ?>

</main>
