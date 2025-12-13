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

	include($racine_path . "templates/db.php");

	// Si un message a été publié sur le forum
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		// Récupération du message envoyé
		$message = trim($_POST['message']);

		// Si le message n'est pas nul
    	if($message !== "") {
        	$stmt = $pdo->prepare("
            	INSERT INTO forum (id_user, date, message)
            	VALUES (?, NOW(), ?)
        	");

        	$stmt->execute([
            	$_SESSION['id'],     // Récupère l'ID de l'utilisateur connecté pour identifier qui a envoyé le message
            	$message
        	]);
    	}
	}

    include($racine_path . "templates/forum.php");

	include($racine_path . "templates/footer.php");
	
?>
