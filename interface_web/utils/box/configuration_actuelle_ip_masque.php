<?php 

    // Récupération du masque de sous-réseau actuel AU FORMAT 255.255.255.0 PAS /24
	$get_subnet_mask_command = 'cat /etc/network/interfaces | grep "netmask" | cut -d" " -f2';
	$current_subnet_mask = trim(shell_exec($get_subnet_mask_command));
	
	// Division du masque de sous-réseau en 4 octets (tableau)
	$current_subnet_mask_octets = explode('.', $current_subnet_mask);

	// Toutes les valeurs possibles pour les octets du masque de sous-réseau
	$valid_subnet_mask_octet_values = [255, 254, 248, 240, 224, 192, 128, 0];

	// Récupération de l'adresse IP actuelle pour pouvoir l'insérer par défaut dans l'input
	$get_ip_command = 'cat /etc/network/interfaces | grep "address" | cut -d" " -f2';
	$current_ip = trim(shell_exec($get_ip_command));

?>