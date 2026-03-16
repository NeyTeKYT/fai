<?php 

    // Fonction qui envoie un message écrit par un utilisateur dans une discussion de la BDD
    function envoyer_message($message, $id_utilisateur, $id_discussion) {

        global $pdo; // Permet d'accéder à la variable globale $pdo

		if($message !== "") {   // Si le message n'est pas nul = contient une information

			// Insertion du message dans la table discussion avec l'ID de l'utilisateur pour savoir qui l'a envoyé
			$stmt = $pdo->prepare("INSERT INTO message (discussion, user, date, message) VALUES (?, ?, NOW(), ?)");
			$stmt->execute([$id_discussion,$_SESSION['id'],$message]);

			// Stocke le message d'information dans une variable de la session de l'utilisateur pour pouvoir le récupérer sur une autre page
			$_SESSION['message'] = "<div class='alert alert-success text-center'>Votre message a bien été publié !</div>";

			// Redirection pour éviter le renvoi du formulaire
			header("Location: " . $racine_path . "discussion.php?id=" . $id_discussion);
			exit;

		}

		// Stocke le message d'information dans une variable de la session de l'utilisateur pour pouvoir le récupérer sur une autre page
		$_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la publication de votre message !</div>";

    }

?>