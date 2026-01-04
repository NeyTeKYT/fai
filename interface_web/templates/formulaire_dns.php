<main class="container my-4">	

	<!-- Titre et description de la page en fonction du mode de configuration de l'utilisateur -->
	<?php if($_SESSION['mode'] === 'debutant') : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">Nom de la box</h1>
    	<p class="text-muted mb-4 text-center">Ce formulaire permet de configurer le nom de la box à partir de son prénom.</p>
	<?php else : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">DNS</h1>
    	<p class="text-muted mb-4 text-center">Ce formulaire permet de configurer le nom de domaine la box et des appareils qui y sont connectés.</p>
	<?php endif; ?>

	<!-- Affichage du bandeau de notification -->
	<?php if(!empty($alerts)) foreach($alerts as $alert) echo $alert; ?>

	<div class="row g-4 mb-4">

		<!-- Card pour la configuration actuelle -->
		<div class="col-12 col-lg-6">
			<div class="card shadow-sm h-100">

				<div class="card-header bg-light text-dark fw-bold text-center">Configuration actuelle</div>

				<div class="card-body">

					<!-- Affichage de la configuration actuelle pour un mode débutant -->
					<?php if($_SESSION['mode'] === 'debutant') : ?>

						<!-- Affichage de l'état du service -->
						<p class="mb-1" id="dns-sate"><strong>État du service :</strong> <?php echo $dns_state_span; ?></p>

						<!-- Affichage du nom de domaine actuellement configuré pour la box -->
						<p class="mb-1" id="domain-name"><strong>Nom configuré pour la box Internet :</strong> <span><?php echo $dns_domain; ?></span></p>

					<!-- Affichage de la configuration actuelle pour un mode avancé -->
					<?php else : ?>

						<!-- Affichage de l'état du service -->
						<p class="mb-1" id="dns-sate"><strong>État du serveur DNS :</strong> <?php echo $dns_state_span; ?></p>

						<!-- Affichage du nom de domaine actuellement configuré pour la box -->
						<p class="mb-1" id="domain-name"><strong>Nom de domaine configuré pour la box Internet :</strong> <span><?php echo $dns_domain; ?></span></p>

					<?php endif; ?>

					<!-- Affichage du nombre d'appareils actuellement connectés -->
					<p class="mb-1" id="nb-users-connected"><strong>Nombre d'appareils connectés :</strong> 0</p>

				</div>
			</div>
		</div>

		<!-- Card pour la liste des appareils actuellement connectés -->
		<div class="col-12 col-lg-6">
			<div class="card shadow-sm h-100">

				<div class="card-header bg-light fw-bold text-center">Appareils connectés</div>

				<!-- Overflow comme dans le forum pour faire défiler les hôtes dans l'espace de la card sans agrandir la page -->
				<div class="card-body" style="max-height: 400px; overflow-y: auto;">

					<!-- Affichage des hôtes connectés à la Box via DHCP si il y en a au moins 1 -->
					<div class="list-group" id="dhcp-hosts-list"></div>

					<!-- Cas où aucun hôte n'est connecté à la box Internet via le serveur DHCP -->
					<p id="no-hosts-msg" class="text-muted text-center">Aucun appareil connecté actuellement.</p>

				</div>
			</div>
		</div>

	</div>

	<div class="row g-4">

        <!-- Card pour modifier le nom de domaine de la box Internet -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">

                <div class="card-header bg-light fw-bold text-center">Nom de domaine de la box Internet</div>

                <div class="card-body">

					<!-- Formulaire pour envoyer le nouveau prénom comme nouvel alias du domaine ceri.com -->
                    <form action="formulaire_dns.php" method="POST">

                        <div class="mb-3 text-center">

							<!-- Champ pour le prénom -->
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" name="first_name" class="form-control text-center mx-auto" style="max-width: 250px;" value="<?= $current_first_name; ?>" required>

                        </div>

                        <button type="submit" class="btn btn-dark w-100">Modifier le nom de domaine de la box Internet</button>

                    </form>
                </div>
            </div>
        </div>

        <!-- Card pour ajouter un sous-domaine pour un appareil connecté à la box Internet pour le mode de configuration avancé-->
		<?php if(($_SESSION['mode']) === 'avance') : ?>

			<div class="col-12 col-lg-6">
				<div class="card shadow-sm h-100">

					<div class="card-header bg-light fw-bold text-center">Ajouter un sous-domaine</div>

					<div class="card-body">

							<form action="formulaire_dns.php" method="POST">

								<!-- Champ pour le nom / l'alias comme sous-domaine du domaine configuré sur la box Internet -->
								<div class="mb-3">
									<label class="form-label">Nom de l’hôte</label>
									<input type="text" name="hostname" class="form-control" placeholder="Alias pour le sous-domaine configuré" required>
								</div>

								<!-- Champ pour l'adresse IP afin de l'associer au sous-domaine configuré -->
								 <div class="mb-3">
									<label class="form-label">Adresse IP</label>
									<input type="text" name="ip" class="form-control" placeholder="Adresse IP de la machine à résoudre" required>
								</div>

								<button type="submit" class="btn btn-dark w-100">Ajouter un sous-domaine</button>

							</form>

					</div>
					
				</div>
			</div>

		<?php endif; ?>

    </div>

	<?php if($_SESSION['mode'] === 'avance') : ?>

		<!-- La card est par défaut cachée au premier chargement mais sera rendue visible via JavaScript -->
		<div id="dns-hosts-card" class="row g-4 mt-2 d-none">
			<div class="col-12">
				<div class="card shadow-sm">

					<div class="card-header bg-light fw-bold text-center">Liste des sous-domaines configurés</div>

					<div class="card-body" style="max-height: 350px; overflow-y: auto;">

						<!-- Affichage des sous-domaines configurés dans un tableau -->
						<table class="table table-hover align-middle mb-0">

							<thead class="table-light">
								<tr>
									<th>Nom d’hôte</th>
									<th>Adresse IP</th>
									<th class="text-end">Actions</th>
								</tr>
							</thead>

							<!-- Sera rempli dynamiquement avec JavaScript -->
							<tbody id="dns-hosts-table"></tbody>

						</table>

						<p id="no-dns-hosts-msg" class="text-muted text-center d-none">Aucun sous-domaine configuré.</p>

					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>

</main>