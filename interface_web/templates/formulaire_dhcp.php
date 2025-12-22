<main class="container my-4">	

	<!-- Titre et rôle de la page -->
	<!-- La version par défaut est la version "débutante" donc j'essaye d'utiliser des termes 
	 simples, différents pour une personne qui ne serait à l'aise avec les termes techniques -->
	<h1 class="mb-4 fw-bold text-dark text-center">Appareils</h1>
    <p class="text-muted mb-4 text-center">Ce formulaire permet de modifier la plage d'adresses des appareils connectés à la box.</p>

	<!-- Affiche la configuration actuelle DHCP -->
	<div class="d-flex justify-content-center">
        <div class="col-12 col-lg-6">

            <div class="card shadow-sm">
                <div class="card-header bg-light text-dark fw-bold text-center">Configuration actuelle</div>
                <div class="card-body">
                    <div class="mb-4">

						<!-- État du serveur DHCP -->
						<p class="mb-1" id="dhcp-state"><strong>Serveur :</strong> <?php echo $dhcp_state_span; ?></p>
						<!-- Plage d'adresses actuellement configurées -->
						<p class="mb-1" id="plage-adresses"><strong>Plage d'adresses :</strong> </span><?php echo $dhcp_range; ?></span></p>
						<!-- Nombre d'utilisateurs connectés via DHCP -->
						<p class="mb-1" id="nb-users-connected"><strong>Nombre d'appareils connectés :</strong> </span><?php echo $dhcp_leases; ?></span></p>
						<!-- Utilisateurs connectés -->
						<p class="mb-1" id="users-connected"><strong>Appareils connectés :</strong> <span><?php echo $dhcp_users; ?></span></p>
						<!-- Bouton pour redémarrer le serveur DHCP -->
						<!--<button class="btn btn-dark w-100 mt-3">Redémarrer le serveur</button>-->

					</div>

					<hr>
	
					<!-- Formulaire de configuration DHCP -->
					<form action="formulaire_dhcp.php" method="POST">
						<div class="mb-3 text-center">

							<!-- Nombre de machines -->
							<label class="form-label fw-bold">Nombre de machines à inclure dans la plage d'adresses :</label><br>
							<!-- Mettre par défaut le nombre de machines configurées sur la plage d'adresses actuelle comme valeur du Input -->
							<input type="number" id="devices_number" name="devices_number" min="1" max="<?php echo $max_value; ?>" value="<?php echo "$current_configured_devices_number"; ?>"><br>	

						</div>
						
						<!-- Bouton pour changer la configuration -->
                        <button type="submit" class="btn btn-dark w-100 mt-3">Soumettre</button>

					</form>

				</div>
			</div>

		</div>
	</div>

</main>