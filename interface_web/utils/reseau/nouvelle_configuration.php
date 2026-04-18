<?php 

    // Cas d'envoi du formulaire
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Soumission du formulaire pour modifier le nom de domaine de la box Internet
		if(!empty($_POST['first_name'])) {

			$first_name = trim($_POST['first_name']);	// Stockage du prénom entré à la soumission du formulaire pour modifier le prénom (alias) de la box Internet

			// Un prénom ne peut pas contenir de chiffres et de caractères spéciaux
			if(!preg_match('/^[a-zA-Z-]+$/', $first_name)) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom ne doit contenir que des lettres et des tirets !</div>";

			// Vérifie que le prénom ne dépasse pas la taille maximum d'un nom de domaine DNS (63)
			else if(strlen($first_name) > 63) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom doit avoir une longueur inférieure à 63 caractères !</div>";
			
			// Gestion du cas où le prénom est le prénom déjà configuré = soumission du formulaire sans rien modifier
			else if(file_exists("/etc/bind/db.$first_name.ceri.com")) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom a déjà été configuré comme domaine !</div>";

			else {

				$first_name_as_dns_prefix = strtolower($first_name);	// L'alias doit être en minuscule

				// Exécution du script Bash avec en argument le nouveau prénom à configurer
				$script_command = "sudo /home/stud/scripts/dns.sh " . escapeshellarg($first_name_as_dns_prefix);
				shell_exec($script_command);

				$current_first_name = $first_name_as_dns_prefix;	// Mise à jour du prénom actuellement configuré
				$dns_domain = $current_first_name . ".ceri.com";	// Mise à jour du nom de domaine de la box Internet

				// Message de configuration différent en fonction du mode de configuration de l'utilisateur
				if($_SESSION['mode'] === 'debutant') $alerts[] = "<div class='alert alert-success text-center'>Le nouveau nom de la box Internet est $current_first_name.ceri.com !</div>";
				else $alerts[] = "<div class='alert alert-success text-center'>Le nouveau nom de domaine de la box Internet est $current_first_name.ceri.com !</div>";
				
			}

		}

		# Cas où le mode de configuration de l'utilisateur est le mode débutant
		if($_SESSION['mode'] === 'debutant') {

			// Stockage du nombre de machines entré par l'utilisateur
			$devices_number = $_POST['devices_number'];	
			
			// Vérifie que le nombre de machines entré par l'utilisateur est bien un entier
			$isInteger = filter_var($devices_number, FILTER_VALIDATE_INT);	

			if($isInteger === false) echo "<h2 class='text-danger fw-bolder text-center'>$devices_number n'est pas un entier !</h2>";	// est aussi vérifié par le type="number"

			// Vérifie que le nombre entré par l'utilisateur ne correspond pas déjà la configuration actuelle
			else if($devices_number == $current_configured_devices_number) echo "<h2 class='text-danger fw-bolder text-center'>Il s'agit de la configuration actuelle !</h2>";

			// Cas où le nombre d'appareils souhaité est supérieur au nombre d'hôtes maximum en fonction du masque de sous-réseau
			else if($devices_number > $max_value) echo "<h2 class='text-danger fw-bolder text-center'>Le masque de sous-réseau actuel $current_subnet_mask ne permet pas d'avoir $devices_number appareils !</h2>";	// est aussi vérifié par le "max=$max_value"
			
			else {
				$script_command = "sudo /home/stud/scripts/dhcp.sh " . escapeshellarg($devices_number) . ' ' . escapeshellarg($network_address);
				exec($script_command, $output, $retval);	// 'exec' au lieu de 'shell_exec' pour récupérer $retval et savoir s'il s'agit de l'adresse IP actuelle ou pas
				if($retval == 2) echo "<h2 class='text-danger fw-bolder text-center'>DHCP ne supporte pas les masques de sous-réseaux /31 et /32 car ils ne permettent pas de configurer respectivement 2 ou 1 machine(s) !</h2>";	// Valeur récupérée grâce à un "exit 2" dans le script Bash
				else if($retval == 3) echo "<h2 class='text-danger fw-bolder text-center'>Impossible de configurer $devices_number appareils avec un masque de sous-réseau $current_subnet_mask !</h2>";
				else if($retval == 4) echo "<h2 class='text-danger fw-bolder text-center'>Impossible de configurer $devices_number adresses à partir de votre adresse IP : $current_ip !</h2>";
				else {
					echo "<h2 class='text-success fw-bolder text-center'>La nouvelle plage d'addresses contient $devices_number machines !</h2>";
					$current_configured_devices_number = $devices_number;
				}
			}

		}

		# Sinon, alors le mode de configuration de l'utilisateur est le mode avancé
		else {

			// Stockage dans des variables des adresses IP pour la configuration personnalisée de la plage d'adresses
			$range_start = $_POST['range_start'];
			$range_end = $_POST['range_end'];

			// Mise en place de la commande avec l'ajout des paramètres
			$cmd = "sudo /home/stud/scripts/dhcp.sh avance " .
			escapeshellarg($range_start) . " " .
			escapeshellarg($range_end) . " " .
			escapeshellarg($network_address);

			exec($cmd, $out, $retval);	// Exécute la commande

			if($retval === 0) echo "<h2 class='text-success text-center'>Plage DHCP mise à jour</h2>";

		}
		
	}

?>