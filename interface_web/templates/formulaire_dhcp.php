<main class="container my-4">	

	<!-- Titre et description en fonction du mode de configuration de l'utilisateur -->
	<?php if($_SESSION['mode'] === 'debutant') : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">Appareils</h1>
		<p class="text-muted mb-4 text-center">Cette page permet de modifier le nombre d'appareils maximum pouvant être connectés à la box.</p>
	<?php else : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">DHCP</h1>
		<p class="text-muted mb-4 text-center">Cette page permet de modifier la plage d'adresses des appareils connectés à la box.</p>
	<?php endif; ?>

	<div class="row g-4 mb-4">

		<!-- Card pour la configuration actuelle -->
		<div class="col-12 col-lg-6">
			<div class="card shadow-sm h-100">

				<div class="card-header bg-light fw-bold text-center">Configuration actuelle</div>
				<div class="card-body">

					<!-- Affichage de la configuration actuelle pour un mode débutant -->
					<?php if($_SESSION['mode'] === 'debutant') : ?>

						<!-- Affichage de l'état du service --> 
						<p class="mb-1" id="dhcp-state"><strong>État du service :</strong> <?= $dhcp_state_span; ?></p>

						<!-- Affichage du nombre d'appareils actuellement configurés -->
						<p class="mb-1" id="plage-adresses"><strong>Nombre d'appareils configurés :</strong> <?= $current_configured_devices_number; ?></p>

					<!-- Affichage de la configuration actuelle pour un mode avancé -->
					<?php else : ?>

						<!-- Affichage de l'état du serveur DHCP --> 
						<p class="mb-1" id="dhcp-state"><strong>État du serveur DHCP :</strong> <?= $dhcp_state_span; ?></p>

						<!-- Affichage de la plage d'adresses actuellement configurées --> 
						<p class="mb-1" id="plage-adresses"><strong>Plage d'adresses :</strong> <?= $dhcp_range; ?></p>

					<?php endif; ?>

					<!-- Affichage du nombre d'appareils actuellement connectés -->
					<p class="mb-1" id="nb-users-connected"><strong>Nombre d'appareils connectés :</strong> <?= $dhcp_leases; ?></p>

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

	<!-- Card du formulaire prenant la longueur des deux cards du dessus -->
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

					<form action="formulaire_dhcp.php" method="POST">

						<!-- Affichage du formulaire pour le mode débutant -->
						<?php if($_SESSION['mode'] === 'debutant') : ?>

							<div class="mb-4 text-center">

								<label class="form-label fw-bold">Nombre de machines à inclure dans la plage d'adresses</label>
								<input type="number" name="devices_number" class="form-control text-center mx-auto" style="max-width: 200px;" min="1" max="<?= $max_value; ?>" value="<?= $current_configured_devices_number; ?>">

							</div>

						<!-- Affichage du formulaire pour le mode avancé -->
						<?php else : ?>

							<div class="mb-4">

								<div class="row g-2">

									<!-- Adresse IP de début de plage -->
									<div class="col">
										<label class="form-label">Adresse de début</label>
										<input type="text" name="range_start" class="form-control" value="<?= explode(' ', $dhcp_range)[0] ?? '' ?>" required>
									</div>

									<!-- Adresse IP de fin de plage -->
									<div class="col">
										<label class="form-label">Adresse de fin</label>
										<input type="text" name="range_end" class="form-control" value="<?= explode(' ', $dhcp_range)[1] ?? '' ?>" required>
									</div>

								</div>
							</div>

						<?php endif; ?>

						<!-- Bouton pour soumettre la nouvelle configuration -->
						<button type="submit" class="btn btn-dark w-100 mt-3">Modifier la plage d'adresses personnalisée</button>

					</form>

				</div>
			</div>

		</div>
	</div>

</main>
