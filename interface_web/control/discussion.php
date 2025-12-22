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

	// Vérifie la validité de l'ID de la discusssion qui est fourni dans l'URL
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		header("Location: ./forum.php");
		exit;
	}

	$discussion_id = (int) $_GET['id'];	// Stockage dans une variable pour pouvoir plus facilement manipuler l'ID

	// Récupération de toutes les informations sur la discussion qui correspond à l'ID
	$stmt_discussion = $pdo->prepare("
	SELECT discussion.id, discussion.title, user.username AS createur FROM discussion 
	INNER JOIN user ON discussion.creator = user.id 
	WHERE discussion.id = ?");
	$stmt_discussion->execute([$discussion_id]);
	$discussion = $stmt_discussion->fetch();

	// Redirige l'utilisateur vers le forum si la discussion n'a pas pu être récupérée
	if(!$discussion) {
		header("Location: forum.php");
		exit;
	}

	// Cas de suppression d'un message
	if (isset($_POST['delete_message_id'])) {

		$message_id = (int) $_POST['delete_message_id'];

		// Vérifie que le message appartient bien à l'utilisateur connecté
		$stmt_check = $pdo->prepare("
			SELECT id FROM message 
			WHERE id = ? AND user = ?
		");
		$stmt_check->execute([$message_id, $_SESSION['id']]);

		if ($stmt_check->fetch()) {

			// Soft delete : remplacement du contenu
			$stmt_delete = $pdo->prepare("
				UPDATE message 
				SET message = '[message supprimé]'
				WHERE id = ?
			");
			$stmt_delete->execute([$message_id]);
		}

		header("Location: discussion.php?id=" . $discussion_id);
		exit;
	}

	// Cas d'envoi d'un message dans la discussion
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$message = trim($_POST['message']);	// Stockage dans une variable du message envoyé pour pouvoir plus facilement le manipuler

		// Si le message n'est pas nul = contient une information
		if($message !== "") {

			// Insertion du message dans la table discussion avec l'ID de l'utilisateur pour savoir qui l'a envoyé
			$stmt_insert = $pdo->prepare("
			INSERT INTO message (discussion, user, date, message) 
			VALUES (?, ?, NOW(), ?)");
			$stmt_insert->execute([$discussion_id,$_SESSION['id'],$message]);

			// Redirection pour éviter le renvoi du formulaire
			header("Location: discussion.php?id=" . $discussion_id);
			exit;

		}
	}

	// Récupération de tous les messages envoyés dans la discussion
	$stmt_messages = $pdo->prepare("
		SELECT message.id, message.message, message.date, user.username, user.id AS user_id, user.role FROM message
		INNER JOIN user ON message.user = user.id
		WHERE message.discussion = ?
		ORDER BY message.id ASC
	");

	$stmt_messages->execute([$discussion_id]);
	$messages = $stmt_messages->fetchAll();

    include($racine_path . "templates/discussion.php");

	include($racine_path . "templates/footer.php");
	
?>
