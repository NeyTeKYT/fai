<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center"><?= htmlspecialchars($discussion['title']) ?></h1>
    <p class="text-muted mb-4 text-center">Discussion lancée par <strong><?= htmlspecialchars($discussion['createur']) ?></strong></p>

    <?php 

        // Require instead of include because if a template file is missing, the page should not continue.
        // If one of these is missing, it's better that the page breaks immediately.
        // Always use __DIR__ to define path relative to your current file.
        require __DIR__ . "/templates/afficher_discussion.php";
        require __DIR__ . "/templates/formulaire_envoi_message.php";

    ?>

</main>
