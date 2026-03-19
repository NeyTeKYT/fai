<?php 

    // Fonction qui ajoute une discussion (titre + message) dans la BDD
    function creer_discussion($titre, $message, $id_utilisateur) {

		global $pdo;	// Permet d'accéder à la variable globale $pdo

		$vecteur_titre = calculer_vecteur($titre);	// Calcule le vecteur binaire du titre par rapport au vocabulaire
		$valeurs_vecteur_titre = "";
		for($i = 0; $i < count($vecteur_titre); $i++) $valeurs_vecteur_titre = $valeurs_vecteur_titre . $vecteur_titre[$i];

        // Création d'un nouvel enregistrement dans la table "discussion"
		$stmt = $pdo->prepare("INSERT INTO discussion (creator, title, vecteur) VALUES (?, ?, ?)");
	    $result_discussion = $stmt->execute([
			$id_utilisateur,    // ID de l'utilisateur connecté pour identifier le créateur de la discussion
			$titre,
			$valeurs_vecteur_titre
		]);

		$id_discussion = $pdo->lastInsertId();	// Récupération de l'ID de la dernière discussion crée

		$vecteur_message = calculer_vecteur($message);	// Calcule le vecteur binaire du message par rapport au vocabulaire
		$valeurs_vecteur_message = "";
		for($i = 0; $i < count($vecteur_message); $i++) $valeurs_vecteur_message = $valeurs_vecteur_message . $vecteur_message[$i];

		// Création d'un nouvel enregistrement dans la table "message"
		$stmt = $pdo->prepare("INSERT INTO message (discussion, user, date, message, vecteur) VALUES (?, ?, NOW(), ?, ?)");
		$result_message = $stmt->execute([
			$id_discussion,
			$_SESSION['id'],     // ID de l'utilisateur connecté pour identifier qui a envoyé le message
			// On ne met pas la date car elle a déjà été renseignée via la fonction NOW()
			$message,
			$valeurs_vecteur_message
		]);

		if($result_discussion && $result_message) {

			// Stocke le message d'information dans une variable de la session de l'utilisateur pour pouvoir le récupérer sur une autre page
			$_SESSION['message'] = "<div class='alert alert-success text-center'>Votre discussion a bien été crée !</div>";

			// Redirection vers la discussion pour suivre les messages envoyés
			header("Location: " . $racine_path . "discussion.php?id=" . $id_discussion);
			exit;

		}

		if(!$result_discussion) $_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la création de la discussion !</div>";
		if(!$result_message) {
			$_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la publication du message !</div>";
			supprimer_discussion($id_discussion);	// Supprime la discussion si le message n'a pas pu être publié
		}

    }

?>