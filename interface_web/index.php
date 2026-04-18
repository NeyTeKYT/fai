<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: control/login.php");
		exit;
	}

	require "utils/index.php";	// Contient les variables contenant les informations utilisées dans la template
	require "utils/box/configuration_actuelle_ip_masque.php";	// Contient l'adresse IP actuelle et le masque de sous-réseau actuel
	require "utils/box/configuration_actuelle_nom_domaine.php";
	require "utils/reseau/configuration_actuelle.php";
	require "utils/securite/security_state.php";

	require "templates/head.php";	// La balise <head> avec toutes les métadonnées 
	require "templates/navbar.php";	// Barre de navigation pour pouvoir se déplacer entre les pages
	require "templates/index/index.php";	// Contient le contenu spécifique de la page d'accueil du FAI
	require "templates/footer.php";	// Footer avec les informations du créateur
	
?>
