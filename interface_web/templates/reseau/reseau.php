<main class="container my-4">

    <!-- Titre et description en fonction du mode de configuration de l'utilisateur -->
	<?php if($_SESSION['mode'] === 'debutant') : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">Réseau</h1>
		<p class="text-muted mb-4 text-center">Configurez votre réseau informatique dimensionné grâce à un nombre d'appareils.</p>
	<?php else : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">DHCP</h1>
		<p class="text-muted mb-4 text-center">Configurez votre plage d'adresses personnalisées DHCP pour votre réseau informatique.</p>
	<?php endif; ?>

	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">

				<!-- Titre de la card en fonction du mode de configuration de l'utilisateur -->
				<?php if($_SESSION['mode'] === 'debutant') : ?>
					<div class="card-header bg-light fw-bold text-center">Configurez le nombre de machines que vous souhaitez connecter à votre box Internet.</div>
				<?php else : ?>
					<div class="card-header bg-light fw-bold text-center">Configurez votre plage d'adresses IP qui seront attribuées aux machines connectés à votre box Internet.</div>
				<?php endif; ?>

				<div class="card-body">

                    <form action="reseau.php" method="POST">

                        <?php 

                            if($_SESSION['mode'] === 'debutant') require __DIR__ . "/templates/nombre_machines.php";
                            else require __DIR__ . "/templates/plage_personnalisee.php";

                        ?>

                        <!-- Bouton pour soumettre la nouvelle configuration -->
						<button type="submit" class="btn btn-dark w-100 mt-3">Modifier la plage d'adresses personnalisée</button>

                    </form>
                
                </div>

            </div>
        </div>
    </div>

</main>
