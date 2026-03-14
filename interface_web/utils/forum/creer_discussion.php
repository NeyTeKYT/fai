<?php 

    // Fonction qui ajoute une discussion (titre + message) dans la BDD
    function creer_discussion($titre, $message, $id_utilisateur) {

		global $pdo;	// Permet d'accéder à la variable globale $pdo

        // Création d'un nouvel enregistrement dans la table "discussion"
		$stmt = $pdo->prepare("INSERT INTO discussion (creator, title) VALUES (?, ?)");
	    $stmt->execute([
			$id_utilisateur,    // ID de l'utilisateur connecté pour identifier le créateur de la discussion
			$titre
		]);

		$id_discussion = $pdo->lastInsertId();	// Récupération de l'ID de la dernière discussion crée

		// Création d'un nouvel enregistrement dans la table "message"
		$stmt = $pdo->prepare("INSERT INTO message (discussion, user, date, message) VALUES (?, ?, NOW(), ?)");
		$stmt->execute([
			$id_discussion,
			$_SESSION['id'],     // ID de l'utilisateur connecté pour identifier qui a envoyé le message
			// On ne met pas la date car elle a déjà été renseignée via la fonction NOW()
			$message
		]);

        // Redirection vers la discussion pour suivre les messages envoyés
		header("Location: " . $racine_path . "discussion.php?id=" . $id_discussion);
		exit;

    }

?>