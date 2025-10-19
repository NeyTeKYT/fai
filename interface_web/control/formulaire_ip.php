<?php

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

	// Cas d'envoi du formulaire
	if($_POST['ip']) {

		$ip = htmlspecialchars($_POST['ip']);	// Stockage de l'adresse IP entrée par l'utilisateur
		
		// Vérification de l'adresse IP entrée par l'utilisateur
		$isValid = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);	// Vérifie qu'il s'agit bien d'une adresse IPv4
		$isPublic = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);	// Vérifie que l'adresse IP n'est pas une adresse privée

		if($isValid === false) echo "<h2 class='resultat red'>$ip n'est pas une adresse IPv4 !</h2>";
		elseif($isPublic !== false) echo "<h2 class='resultat red'>$ip n'est pas une adresse privée !</h2>";	// Il faut que l'adresse IPv4 soit privée car eth1 est une interface "Réseau Interne"

		else {
			$script_command = "sudo /home/stud/FAI/IP/ip.sh " . escapeshellarg($ip);
			exec($script_command, $output, $retval);	// 'exec' au lieu de 'shell_exec' pour récupérer $retval et savoir s'il s'agit de l'adresse IP actuelle ou pas
			if($retval == 2) echo "<h2 class='resultat red'>Les adresses IP sont les mêmes !</h2>";	// Valeur récupérée grâce à un exit(2) dans le script Bash
			else {
				echo "<h2 class='resultat green'>La nouvelle adresse IP de la box internet est $ip !</h2>";
			}
		}
	}

	//echo nl2br(file_get_contents('/etc/network/interfaces'));	// Affichage du fichier /etc/network/interfaces pour bien montrer la modification effectuée

	// Récupération de l'adresse IP actuelle pour pouvoir l'insérer par défaut dans l'input
	$get_ip_command = 'cat /etc/network/interfaces | grep "address" | cut -d" " -f2';
	$current_ip = htmlspecialchars(shell_exec($get_ip_command));

	include($racine_path . "templates/formulaire_ip.php");

	include($racine_path . "templates/footer.php");
	
?>
