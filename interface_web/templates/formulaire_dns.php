<main>	
	<h1>Formulaire DNS</h1>
    <p>Ce formulaire permet de configurer son domaine à partir de son prénom.</p>
	<form action="formulaire_dns.php" method="POST">

        <!-- Prénom -->
		<label for="first_name">Prénom :</label><br>
        <!-- Mettre par défaut le prénom configuré comme préfixe du domaine actuelle comme valeur de l'Input -->
		<input type="text" id="first_name" name="first_name" value="<?php echo $current_first_name; ?>"><br>	

		<input type="submit" value="Soumettre">
	</form>
</main>