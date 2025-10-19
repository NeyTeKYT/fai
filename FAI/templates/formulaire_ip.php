<main>	
	<h1>Formulaire IP</h1>
    <p>Ce formulaire permet de modifier l'adresse IP de la machine.</p>
	<form action="formulaire_ip.php" method="POST">

        <!-- Adresse IP -->
		<label for="ip">Adresse IP :</label><br>
		<input type="text" id="ip" name="ip" value="<?php echo "$current_ip"; ?>"><br>   <!-- Mettre par défaut l'adresse IP actuelle comme valeur du Input -->

		<input type="submit" value="Soumettre">
	</form>
</main>