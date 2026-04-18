<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

    require $racine_path . "/utils/securite/security_state.php";
    require $racine_path . "utils/securite/securite.php";

    include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 
	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages
	include($racine_path . "templates/securite/securite.php");    // Contient le contenu spécifique de la page
	include($racine_path . "templates/footer.php"); // Footer avec les informations du créateur
	
?>
