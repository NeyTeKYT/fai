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

	// Récupération du rôle de l'utilisateur connecté
	$stmt_role = $pdo->prepare("SELECT role FROM user WHERE id = ?");
	$stmt_role->execute([$_SESSION['id']]);
	$user_role = $stmt_role->fetchColumn();

	// Si un message a été publié sur le forum
	// N'autorise pas un technicien à ouvrir une discussion = il répond aux problèmes des utilisateurs 
	if($_SERVER['REQUEST_METHOD'] === 'POST' && $user_role !== 'technicien') {
		
		// Récupération du titre de la discussion
		$titre = trim($_POST['titre']);
		// Récupération du message envoyé
		$message = trim($_POST['message']);

		// Si le message n'est pas nul
    	if(($titre !== "") && ($message !== "")) {

			// Création d'un nouvel enregistrement dans la table "discussion"
			$stmt_discussion = $pdo->prepare("
				INSERT INTO discussion (creator, title) 
				VALUES (?, ?)
			");

			// Exécute la requête
			$stmt_discussion->execute([
				$_SESSION['id'],     // Récupère l'ID de l'utilisateur connecté pour identifier qui a crée la discussion
				$titre
			]);

			$id_discussion = $pdo->lastInsertId();	// Récupération de l'ID de la dernière discussion crée

			// Création d'un nouvel enregistrement dans la table "message"
        	$stmt_message = $pdo->prepare("
            	INSERT INTO message (discussion, user, date, message) 
				VALUES (?, ?, NOW(), ?)
        	");

			// Exécute la requête
        	$stmt_message->execute([
				$id_discussion,
            	$_SESSION['id'],     // Récupère l'ID de l'utilisateur connecté pour identifier qui a envoyé le message
				// On ne met pas la date car elle a déjà été renseignée via la fonction NOW()
            	$message
        	]);

			// Redirection vers la discussion pour suivre les messages envoyés
			header("Location: discussion.php?id=" . $id_discussion);
			exit;

    	}
	}

	// Récupération de toutes les discussions
    $stmt_discussions = $pdo->query("
    	SELECT discussion.id, discussion.title, user.username AS createur FROM discussion 
		INNER JOIN `user` ON discussion.creator = user.id ORDER BY discussion.id DESC
    ");

    $discussions = $stmt_discussions->fetchAll();	// Exécute la requête

	// Pour chaque discussion, 
	foreach($discussions as &$discussion) {

		// Récupération de la date de création de la discussion
		$stmt_date_first_message = $pdo->prepare("
			SELECT date FROM message WHERE discussion = ? ORDER BY id ASC LIMIT 1
		");

		$stmt_date_first_message->execute([$discussion['id']]);
		$date_first_message = $stmt_date_first_message->fetch();

		// Ajout de la date récupérée comme valeur de $discussion
		$discussion['date_creation'] = $date_first_message ? $date_first_message['date'] : null;

		// Récupération du dernier message publié dans la discussion et son utilisateur
		$stmt_last_message = $pdo->prepare("
			SELECT message.message, message.date, user.username FROM message 
			INNER JOIN `user` ON message.user = user.id WHERE message.discussion = ?
			ORDER BY message.id DESC LIMIT 1	
		");

		$stmt_last_message->execute([$discussion['id']]);
		$last_message = $stmt_last_message->fetch();

		$discussion['dernier_message'] = $last_message['message'] ?? '';
		$discussion['date_dernier_message'] = $last_message['date'] ?? '';
		$discussion['dernier_auteur'] = $last_message['username'] ?? '';

	}

	unset($discussion);

    include($racine_path . "templates/forum.php");

	include($racine_path . "templates/footer.php");
	
?>
