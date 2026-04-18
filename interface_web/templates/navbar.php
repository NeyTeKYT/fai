<?php 

	// Stockage du mode de l'utilisateur, sinon il aura par défaut le mode débutant de configuré.
	$mode = $mode ?? 'debutant';

?>

<header>
  	<nav class="navbar navbar-expand-lg navbar-light bg-light">
    	<div class="container">

			<!-- Affichage du nom (hostname) de la box Internet -->
      		<a class="navbar-brand fw-bold text-dark" href="/interface_web/index.php"><?php echo trim(shell_exec("hostname")); ?></a>

      		<!-- Version responsive pour l'affichage de l'interface web sur un téléphone -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
				aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

      		<!-- Onglets -->
      		<div class="collapse navbar-collapse" id="navbarContent">
       			<ul class="navbar-nav ms-auto mt-2 mb-2 mb-lg-0">
					
					<!-- Onglet pour accéder au tableau de bord -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/index.php" data-toggle="tooltip" data-placement="bottom"
						title="Visualisation de l'état de la box Internet.">Tableau de bord</a>
					</li>

					<!-- Onglet pour accéder à l'onglet de configuration de la box -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/box.php" data-toggle="tooltip" data-placement="bottom"
						title="Configuration des informations la box Internet.">Box</a>
					</li>

					<!-- Onglet pour accéder à l'onglet de configuration du réseau -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/reseau.php" data-toggle="tooltip" data-placement="bottom"
						title="Configuration du réseau.">Réseau</a>
					</li>

					<!-- Onglet pour accéder à l'onglet de configuration des appareils connectés à la box -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/appareils.php" data-toggle="tooltip" data-placement="bottom"
						title="Configuration des appareils connectés à la box Internet.">Appareils</a>
					</li>

					<!-- Onglet pour accéder à l'onglet de configuration de la sécurité -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/securite.php" data-toggle="tooltip" data-placement="bottom"
						title="Configuration de la sécurité.">Sécurité</a>
					</li>

					<!-- Onglet pour accéder au forum -->
					<li class="nav-item">

						<!-- Nom d'onglet différent pour le forum en fonction du mode utilisé -->
						<?php if($mode === 'debutant') : ?>
							<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/forum.php" data-toggle="tooltip" data-placement="bottom"
							title="Posez vos questions aux techniciens et autres clients du FAI.">Aide</a>
						<?php else : ?>
							<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/forum.php" data-toggle="tooltip" data-placement="bottom"
							title="Accès au forum pour poser vos questions et régler vos problèmes.">Forum</a>
						<?php endif; ?>

					</li>

					<!-- Onglet pour accéder aux paramètres de l'utilisateur --> 
					<li class="nav-item">
						<a class="nav-link btn btn-light text-secondary fw-bold" href="/interface_web/control/parametres.php" data-toggle="tooltip" data-placement="bottom"
						title="Accès aux paramètres pour modifier vos données et le mode de configuration de la box.">Paramètres</a>
					</li>

          			<!-- Onglet de déconnexion -->
					<li class="nav-item">
						<a class="nav-link btn btn-light ms-2 text-danger fw-bold" href="/interface_web/control/logout.php" data-toggle="tooltip" data-placement="bottom"
						title="Se déconnecter une fois l'usage de l'interface web terminé.">Déconnexion</a>
					</li>

        		</ul>
      		</div>
			
    	</div>
  	</nav>
</header>
