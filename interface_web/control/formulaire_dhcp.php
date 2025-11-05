<?php

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

	// Récupération du nombre de machines déjà configurées
	$get_configured_devices_number = 'cat /etc/dhcp/dhcpd.conf | grep "# Nombre de machines configurées" | cut -d":" -f2';
	$current_configured_devices_number = htmlspecialchars(shell_exec($get_configured_devices_number));

	// Cas d'envoi du formulaire
	if($_POST['devices_number']) {

		$devices_number = htmlspecialchars($_POST['devices_number']);	// Stockage du nombre de machines entré par l'utilisateur
		
		// Vérification du nombre de machines entré par l'utilisateur
		$isInteger = filter_var($devices_number, FILTER_VALIDATE_INT);	// Vérifie que c'est bien un entier

		if($isInteger === false) echo "<h2 class='resultat red'>$devices_number n'est pas un entier !</h2>";

		# Vérifie que le nombre entré par l'utilisateur de la configuration actuelle
		else if($devices_number === $current_configured_devices_number) echo "<h2 class='resultat red'>La configuration de la plage d'adresses est déjà mise en place avec $current_configured_devices_number !</h2>";
		
		else {
			$script_command = "sudo /home/stud/scripts/dhcp.sh " . escapeshellarg($devices_number);
			exec($script_command, $output, $retval);	// 'exec' au lieu de 'shell_exec' pour récupérer $retval et savoir s'il s'agit de l'adresse IP actuelle ou pas
			if($retval == 2) echo "<h2 class='resultat red'>Un trop grand nombre de machines ne peut être configuré !</h2>";	// Valeur récupérée grâce à un exit(2) dans le script Bash
			else {
				echo "<h2 class='resultat green'>La nouvelle plage d'addresses contient $devices_number machines !</h2>";
			}
		}
	}

	// Récupération du nombre de machines déjà configurées
	$get_configured_devices_number = 'cat /etc/dhcp/dhcpd.conf | grep "# Nombre de machines configurées" | cut -d":" -f2';
	$current_configured_devices_number = htmlspecialchars(shell_exec($get_configured_devices_number));

    include($racine_path . "templates/formulaire_dhcp.php");

	include($racine_path . "templates/footer.php");
	
?>
