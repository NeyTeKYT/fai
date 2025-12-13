<header>
  	<nav class="navbar navbar-expand-lg navbar-light bg-light">
    	<div class="container">

			<!-- Affichage du nom (hostname) de la box Internet -->
      		<a class="navbar-brand fw-bold text-dark" href="/interface_web/index.php"><?php echo trim(shell_exec("hostname")); ?></a>

      		<!-- Toggle pour mobile -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
				aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

      		<!-- Onglets -->
      		<div class="collapse navbar-collapse" id="navbarContent">
       			<ul class="navbar-nav ms-auto mt-2 mb-2 mb-lg-0">
					
					<!-- Onglet pour accéder au panel d'administration -->
					<li class="nav-item">
            			<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/index.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Informations générales et simples fonctionnalités.">Administration</a>
					</li>

					<!-- Onglet pour accéder au formulaire IP -->
          			<li class="nav-item">
            			<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/formulaire_ip.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Configuration de l'adresse IP et du masque de sous-réseau de la box.">Adresse</a>
					</li>

					<!-- Onglet pour accéder au formulaire DHCP -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/formulaire_dhcp.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Configuration du nombre d'appareils à allouer.">Appareils</a>
					</li>

					<!-- Onglet pour accéder au formulaire DNS -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/formulaire_dns.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Configurer le nom de domaine de la box.">Nom</a>
					</li>

					<!-- Onglet pour accéder à la page de mesure du débit -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/diagnostic.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Mesurer le débit du réseau.">Diagnostic</a>
					</li>

					<!-- Onglet pour accéder au forum -->
					<li class="nav-item">
						<a class="nav-link btn btn-light text-dark fw-bold" href="/interface_web/control/forum.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Accès au forum pour poser vos questions.">Aide</a>
					</li>

          			<!-- Déconnexion -->
					<li class="nav-item">
						<a class="nav-link btn btn-light ms-2 text-danger fw-bold" href="/interface_web/control/logout.php"
						data-toggle="tooltip" data-placement="bottom"
						title="Se déconnecter une fois l'usage de l'interface web terminé.">Déconnexion</a>
					</li>

        		</ul>
      		</div>
    	</div>
  	</nav>
</header>
