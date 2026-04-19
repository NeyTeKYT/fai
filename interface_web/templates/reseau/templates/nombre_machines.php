<!-- Affichage du formulaire pour le mode débutant -->
<div class="mb-4 text-center">

	<label class="form-label fw-bold">Nombre de machines à inclure dans la plage d'adresses</label>
	<input type="number" name="devices_number" class="form-control text-center mx-auto" style="max-width: 200px;" min="1" max="<?= $max_value; ?>" value="<?= $current_configured_devices_number; ?>">

</div>