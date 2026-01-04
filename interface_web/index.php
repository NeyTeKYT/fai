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

	// État du serveur web Apache
	$apache_state = trim(shell_exec("systemctl is-active apache2 2>/dev/null"));	
	if($apache_state == "active") $apache_state_span = "<span class='text-success fw-bolder'>actif</span>";
	else $apache_state_span = "<span class='text-danger fw-bolder'>innactif</span>";

	include("templates/main_index.php");	// Contient le contenu spécifique de la page d'accueil du FAI

	include("templates/footer.php");	// Footer avec les informations du créateur
	
?>
