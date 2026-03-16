<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	// Vérifie la validité de l'ID de la discusssion qui est fourni dans l'URL
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		header("Location: ./forum.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/db.php");

	include($racine_path . "utils/discussion/supprimer_message.php");	// Fonction qui supprime un message d'une discussion du forum de la BDD
	include($racine_path . "utils/discussion/envoyer_message.php");	// Fonction qui envoie un message écrit par un utilisateur dans une discussion de la BDD
	include($racine_path . "utils/discussion/recuperer_messages.php");	// Fonction qui récupère tous les messages d'une discussion et les retourne

	$id_discussion = $_GET['id'];	// Stockage dans une variable de l'ID de la discussion
	$id_utilisateur = $_SESSION['id'];	// Stockage dans une variable de l'ID de l'utilisateur connecté

	// Récupération de toutes les informations sur la discussion
	$stmt = $pdo->prepare("
		SELECT discussion.id, discussion.title, user.username AS createur FROM discussion 
		INNER JOIN user ON discussion.creator = user.id 
		WHERE discussion.id = ?");
	$stmt->execute([$id_discussion]);
	$discussion = $stmt->fetch();

	// Suppression d'un message
	if(isset($_POST['supprimer_message'])) supprimer_message($_POST['supprimer_message'], $id_utilisateur, $id_discussion);

	// Envoi d'un message
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) envoyer_message($_POST['message'], $id_utilisateur, $id_discussion);

	$messages = recuperer_messages($id_discussion);

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 
	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

	// Affiche un message d'erreur si la discussion avec l'ID passé en paramètre n'a pas bien été récupérée
	if(!$discussion) {
		echo "<div class='alert alert-danger text-center'>Discussion introuvable !</div>";
		exit;
	}

	// Vérifie si une action a été effectuée par l'utilisateur 
	if(isset($_SESSION['message'])) {
		echo $_SESSION['message'];
		unset($_SESSION['message']);	// Supprime le message pour qu'il ne soit diffusé qu'une seule fois
	}

    include($racine_path . "templates/discussion/discussion.php");	// Contient le template de la discussion
	include($racine_path . "templates/footer.php");	// Footer avec les informations du créateur
	
?>
