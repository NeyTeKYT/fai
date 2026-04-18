<?php 

    // Par défaut à 0, sera incrémenté / décrémenté automatiquement par Ajax dans le fichier control/dhcp_hosts.php
	$dhcp_leases = 0;

	// Récupération de la plage d'adresses DHCP actuellement configurées
	$dhcp_range = trim(shell_exec("grep 'range' /etc/dhcp/dhcpd.conf | awk '{print $2, $3}' | cut -d';' -f1"));	
	$dhcp_range = str_replace(' ', ' - ', $dhcp_range);

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

?>