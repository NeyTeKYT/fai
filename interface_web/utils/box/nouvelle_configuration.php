<?php 

    // Cas d'envoi du formulaire
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$alerts = [];	// Stockage des messages du bandeau de notification dans un tableau

		// Récupère les valeurs des 4 octets du masque de sous-réseau
		$subnet_mask_octets = [
			intval($_POST['subnet_mask_octet1'] ?? 0),
			intval($_POST['subnet_mask_octet2'] ?? 0),
			intval($_POST['subnet_mask_octet3'] ?? 0),
			intval($_POST['subnet_mask_octet4'] ?? 0),
		];

		// Reformate le masque de sous-réseau en concaténant les 4 octets
		$subnet_mask = implode('.', $subnet_mask_octets);

		// Vérifie que chaque octet a une valeur valide
		$areSubnetMaskOctetsValuesValid = true;
		foreach($subnet_mask_octets as $octet_value) {
			if(!in_array($octet_value, $valid_subnet_mask_octet_values)) {
				$areSubnetMaskOctetsValuesValid = false;
				break;
			}
		}

		// Vérifie que le masque de sous-réseau est bien "consécutif" en binaire (pas de 0 avant la fin des 1)
		$binary_subnet_mask = '';
		foreach(explode('.', $subnet_mask) as $octet) $binary_subnet_mask .= str_pad(decbin($octet), 8, '0', STR_PAD_LEFT);	// Convertie chaque octet en binaire
		$isSubnetMaskValid = preg_match('/^1*0*$/', $binary_subnet_mask);	// Vérifie qu'il n'y a pas une mauvaise transition de 0 et de 1

		// Récupère les valeurs des 4 octets de l'adresse IP
		$ip_address_octets = [
			intval($_POST['ip_octet1'] ?? 0),
			intval($_POST['ip_octet2'] ?? 0),
			intval($_POST['ip_octet3'] ?? 0),
			intval($_POST['ip_octet4'] ?? 0),
		];

		// Reformate le masque de sous-réseau en concaténant les 4 octets
		$ip = implode('.', $ip_address_octets);
		
		// Vérification de l'adresse IP entrée par l'utilisateur
		$isIpValid = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);	// Vérifie qu'il s'agit bien d'une adresse IPv4
		$isIpPublic = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);	// Vérifie que l'adresse IP n'est pas une adresse privée

		// Traite les messages d'erreurs
		if((!$areSubnetMaskOctetsValuesValid) || (!$isSubnetMaskValid)) $alerts[] = "<div class='alert alert-danger text-center'>$subnet_mask n'est pas un masque de sous-réseau !</div>";
		else if($isIpValid === false) $alerts[] = "<div class='alert alert-danger text-center'>$ip n'est pas une adresse IPv4 !</div>";
		else if($isIpPublic !== false) $alerts[] = "<div class='alert alert-danger text-center'>$ip n'est pas une adresse privée !</div>";	// Il faut que l'adresse IPv4 soit privée car eth1 est une interface "Réseau Interne"
		else if(($current_subnet_mask === $subnet_mask) && ($current_ip === $ip)) $alerts[] = "<div class='alert alert-danger text-center'>Il s'agit de la configuration actuelle !</div>";

		// Exécute le script pour modifier les informations de l'interface eth1
		else {
			$script_command = "sudo /home/stud/scripts/ip.sh " . escapeshellarg($ip) . ' ' . escapeshellarg($subnet_mask);
			shell_exec($script_command);

			echo "<div class='alert alert-success text-center'>Le masque de sous-réseau est désormais <strong>$subnet_mask</strong> !<br>L'adresse IP est désormais <strong>$ip</strong></div>";
			
			$current_subnet_mask = $subnet_mask;
			$current_ip = $ip;
		}
	}

?>