<div class="col-12 col-lg-6">
	<div class="card shadow-sm h-100">

		<div class="card-header bg-light fw-bold text-center">Ajouter un sous-domaine</div>

			<div class="card-body">

				<form action="appareils.php" method="POST">

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