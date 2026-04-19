<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	require $racine_path . "utils/reseau/configuration_actuelle.php";
	require $racine_path . "utils/reseau/nouvelle_configuration.php";

	require $racine_path . "templates/head.php";	// La balise <head> avec toutes les métadonnées 
	require $racine_path . "templates/navbar.php";	// Barre de navigation pour pouvoir se déplacer entre les pages
    require $racine_path . "templates/reseau/reseau.php";	// Contient le formulaire DHCP
	require $racine_path . "templates/footer.php";	// Footer avec les informations du créateur
	
?>
