<div class="row g-4 mb-4">

    <?php 

        // Require instead of include because if a template file is missing, the page should not continue.
        // If one of these is missing, it's better that the page breaks immediately.
        // Always use __DIR__ to define path relative to your current file.
        require __DIR__ . "/profil/creer_profil.php";
        require __DIR__ . "/profil/liste_profils.php";

    ?>

</main>
