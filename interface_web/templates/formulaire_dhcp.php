<main>	
	<h1>Formulaire DHCP</h1>
    <p>Ce formulaire permet de modifier la plage d'adresses pour les hôtes DHCP.</p>
	<form action="formulaire_dhcp.php" method="POST">

        <!-- Nombre de machines -->
		<label for="devices_number">Nombre de machines à inclure dans la plage d'adresses :</label><br>
        <!-- Mettre par défaut le nombre de machines configurées sur la plage d'adresses actuelle comme valeur du Input -->
		<input type="text" id="devices_number" name="devices_number" value="<?php echo "$current_configured_devices_number"; ?>"><br>   

		<input type="submit" value="Soumettre">
	</form>
</main>