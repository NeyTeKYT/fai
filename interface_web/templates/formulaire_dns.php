<main class="container my-4">	

	<!-- Titre et rôle de la page -->
	<h1 class="mb-4 fw-bold text-dark text-center">Formulaire DNS</h1>
    <p class="text-muted mb-4 text-center">Ce formulaire permet de configurer son domaine à partir de son prénom.</p>

	<!-- Affiche la configuration actuelle DNS -->
	<div class="d-flex justify-content-center">
        <div class="col-12 col-lg-6">

            <div class="card shadow-sm">
                <div class="card-header bg-light text-dark fw-bold text-center">Configuration actuelle</div>
                <div class="card-body">
                    <div class="mb-4">

						<!-- État du serveur DNS -->
						<p id="dns-sate"><strong>Serveur DNS :</strong> <?php echo $dns_state_span; ?></p>
						<!-- Nom de domaine actuellement configuré -->
						<p id="domain-name"><strong>Nom de domaine configuré :</strong> <span><?php echo $dns_domain; ?></span></p>
						<!-- Bouton pour redémarrer le serveur DNS -->
						<button class="btn btn-dark w-100">Redémarrer DNS</button>

					</div>
					
					<hr>

					<!-- Formulaire de configuration DNS -->
					<form action="formulaire_dns.php" method="POST">
						<div class="mb-3 text-center">

							<!-- Prénom -->
							<label class="form-label fw-bold">Prénom :</label><br>
							<!-- Mettre par défaut le prénom configuré comme préfixe du domaine actuelle comme valeur de l'Input -->
							<input type="text" id="first_name" name="first_name" value="<?php echo $current_first_name; ?>"><br>	

						</div>

						<!-- Bouton pour changer la configuration -->
                        <button type="submit" class="btn btn-dark w-100 mt-3">Soumettre</button>
					</form>

				</div>
			</div>

		</div>
	</div>
</main>