<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: control/login.php");
		exit;
	}

	include("templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include("templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

	// Récupération des informations et stockage dans des variables
	$current_hostname = trim(shell_exec("hostname"));	// Hostname
	$uptime = trim(shell_exec("uptime -p | cut -d'p' -f 2"));	// Uptime
	$os_version = trim(shell_exec("lsb_release -d | cut -f2"));	// Version de l'OS
	$mac_address = trim(shell_exec("cat /sys/class/net/eth0/address"));	// Adresse MAC
	$system_date = trim(shell_exec("date"));	// Date du système

	// État d'Apache
	$apache_state = trim(shell_exec("systemctl is-active apache2 2>/dev/null"));	
	if($apache_state == "active") $apache_state_span = "<span class='text-success fw-bolder'>$apache_state</span>";
	else $apache_state_span = "<span class='text-danger fw-bolder'>$apache_state</span>";

	// État du DHCP
	/*$dhcp_state = trim(shell_exec("systemctl is-active isc-dhcp-server 2>/dev/null"));	
	if($dhcp_state == "active") $dhcp_state_span = "<span class='resultat green'>$dhcp_state</span>";
	else $dhcp_state_span = "<span class='resultat red'>$dhcp_state</span>";

	$dhcp_range = trim(shell_exec("grep 'range' /etc/dhcp/dhcpd.conf | awk '{print $2, $3}' | cut -d';' -f1"));	// Plage d'adresses DHCP
	$dhcp_leases = trim(shell_exec("cat /var/lib/dhcp/dhcpd.leases | grep 'lease' | grep '{' | cut -d' ' -f 2 | uniq | wc -l"));	// Nombre de clients DHCP actifs
	$dhcp_users = shell_exec("cat /var/lib/dhcp/dhcpd.leases | grep 'client-hostname' | cut -d'-' -f2 | cut -d' ' -f2 | cut -d'\"' -f2 | uniq");	// Liste des clients DHCP

	// État du DNS
	$dns_state = trim(shell_exec("systemctl is-active bind9 2>/dev/null"));	
	if($dns_state == "active") $dns_state_span = "<span class='resultat green'>$dns_state</span>";
	else $dns_state_span = "<span class='resultat red'>$dns_state</span>";

	// Nom de domaine configuré
	$current_first_name = trim(shell_exec("cat /etc/bind/named.conf.local | grep 'zone' | grep 'ceri.com' | cut -d ' ' -f 2 | cut -d '.' -f 1 | cut -d '\"' -f 2"));
	$dns_domain = $current_first_name . ".ceri.com";*/

	include("templates/main_index.php");	// Contenu spécifique à la page d'accueil du FAI

	include("templates/footer.php");	// Footer avec les informations du créateur
	
?>
