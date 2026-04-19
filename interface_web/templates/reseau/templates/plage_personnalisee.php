<!-- Affichage du formulaire pour le mode avancé -->
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
