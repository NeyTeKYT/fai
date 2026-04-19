<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Sécurité</h1>
    <p class="text-muted mb-4 text-center">Cette page permet de protéger votre réseau et de vous connecter à Internet.</p>

    <!-- Affichage du bandeau de notification -->
	<?php if(!empty($alerts)) foreach($alerts as $alert) echo $alert; ?>

    <div class="row g-4 mb-4">

        <?php 

            require __DIR__ . "/templates/pare-feu.php";

            require __DIR__ . "/templates/lien_vers_controle_parental.php";
            
            if($_SESSION['mode'] === 'avance') {
                require __DIR__ . "/templates/port_forwarding.php";
                require __DIR__ . "/templates/liste_ports_forwarding.php";
            }

        ?>

    </div>

</main>
