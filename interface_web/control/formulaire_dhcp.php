<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

	// État du DHCP
	$dhcp_state = trim(shell_exec("systemctl is-active isc-dhcp-server 2>/dev/null"));	
	if($dhcp_state == "active") $dhcp_state_span = "<span class='text-success fw-bolder'>actif</span>";
	else $dhcp_state_span = "<span class='text-danger fw-bolder'>innactif</span>";

	// Par défaut à 0, sera incrémenté / décrémenté automatiquement par Ajax dans le fichier control/dhcp_hosts.php
	$dhcp_leases = 0;

	// Récupération de la plage d'adresses DHCP actuellement configurées
	$dhcp_range = trim(shell_exec("grep 'range' /etc/dhcp/dhcpd.conf | awk '{print $2, $3}' | cut -d';' -f1"));	

	// Récupère l'adresse IP
	$get_ip_command = 'cat /etc/network/interfaces | grep "address" | cut -d" " -f2';
	$current_ip = trim(shell_exec($get_ip_command));
	// Sépare l'IP en 4 octets
	$ip_address_octets = array_map('intval', explode('.', $current_ip));

	// Récupère le masque de sous-réseau
	$get_subnet_mask_command = 'cat /etc/network/interfaces | grep "netmask" | cut -d" " -f2';
	$current_subnet_mask = trim(shell_exec($get_subnet_mask_command));
	// Sépare le masque de sous-réseau en 4 octets
	$subnet_mask_octets = array_map('intval', explode('.', $current_subnet_mask));

	// Calcule l'adresse réseau
	$network_address = sprintf(
		"%d.%d.%d.%d",
		($ip_address_octets[0] & $subnet_mask_octets[0]),
		($ip_address_octets[1] & $subnet_mask_octets[1]),
		($ip_address_octets[2] & $subnet_mask_octets[2]),
		($ip_address_octets[3] & $subnet_mask_octets[3])
	);

	// Calcule le CIDR 
	$subnet_mask_binary = '';
	foreach ($subnet_mask_octets as $octet) $subnet_mask_binary .= str_pad(decbin((int)$octet), 8, '0', STR_PAD_LEFT);
	$cidr = substr_count($subnet_mask_binary, '1');

	# Calcule le nombre d'hôtes maximum à partir du CIDR
	$max_value = pow(2, 32 - $cidr) - 2;

	// Récupération du nombre de machines déjà configurées
	$get_configured_devices_number = 'cat /etc/dhcp/dhcpd.conf | grep "# Nombre de machines configurées" | cut -d":" -f2';
	$current_configured_devices_number = trim(shell_exec($get_configured_devices_number));

	// Cas d'envoi du formulaire
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    include($racine_path . "templates/formulaire_dhcp.php");	// Contient le formulaire DHCP

	include($racine_path . "templates/footer.php");	// Footer avec les informations du créateur
	
?>
