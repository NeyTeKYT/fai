<?php 

    // Récupération des informations et stockage dans des variables
	$current_hostname = trim(shell_exec("hostname"));	// Hostname
	$uptime = trim(shell_exec("uptime -p | cut -d'p' -f 2"));	// Uptime
	$os_version = trim(shell_exec("lsb_release -d | cut -f2"));	// Version de l'OS
	$mac_address = trim(shell_exec("cat /sys/class/net/eth0/address"));	// Adresse MAC
	$system_date = trim(shell_exec("date"));	// Date du système

	// État du serveur web Apache
	$apache_state = trim(shell_exec("systemctl is-active apache2 2>/dev/null"));	
	if($apache_state == "active") $apache_state_span = "<span class='text-success fw-bolder'>actif</span>";
	else $apache_state_span = "<span class='text-danger fw-bolder'>innactif</span>";

    // État du serveur DHCP
	$dhcp_state = trim(shell_exec("systemctl is-active isc-dhcp-server 2>/dev/null"));	
	if($dhcp_state == "active") $dhcp_state_span = "<span class='text-success fw-bolder'>actif</span>";
	else $dhcp_state_span = "<span class='text-danger fw-bolder'>innactif</span>";

    // État du serveur DNS
	$dns_state = trim(shell_exec("systemctl is-active bind9 2>/dev/null"));	
	if($dns_state == "active") $dns_state_span = "<span class='text-success fw-bolder'>actif</span>";
	else $dns_state_span = "<span class='text-danger fw-bolder'>innactif</span>";

?>