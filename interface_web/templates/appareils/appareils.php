<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Appareils</h1>
    <p class="text-muted mb-4 text-center">Controllez les appareils connectés à votre box Internet.</p>

    <!-- Affichage du bandeau de notification -->
	<?php if(!empty($alerts)) foreach($alerts as $alert) echo $alert; ?>

    <div class="row g-4 mb-4 justify-content-center">
        <?php 
            require __DIR__ . "/templates/liste_appareils.php";
            if($_SESSION['mode'] === 'avance') require __DIR__ . "/templates/ajouter_sous_domaine.php";
        ?>
    </div>

    <?php if($_SESSION['mode'] === 'avance') : ?>
        <div class="row g-4">
            <div class="col-12">
                <?php require __DIR__ . "/templates/liste_sous_domaines.php"; ?>
            </div>
        </div>
    <?php endif; ?>

</main>
