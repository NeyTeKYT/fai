<?php

    // Fonction qui supprime un message d'une discussion du forum de la BDD
    function supprimer_message($id_message, $id_utilisateur, $id_discussion) {

        global $pdo;	// Permet d'accéder à la variable globale $pdo

        // Vérifie que le message appartient bien à l'utilisateur connecté
		$stmt = $pdo->prepare("SELECT id FROM message WHERE id = ? AND user = ?");
		$stmt->execute([$id_message, $id_utilisateur]);

		if($stmt->fetch()) {

            // Supprime le message de la discussion
			$stmt = $pdo->prepare("DELETE FROM message WHERE id = ?");
			$stmt->execute([$id_message]);

            // Compte le nombre de messages restants dans la discussion après la suppression du message
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM message WHERE discussion = ?");
            $stmt->execute([$id_discussion]);
            $nb_messages_restants = $stmt->fetchColumn();

            // Supprime la discussion si il n'y a plus de message dans la discussion
            if($nb_messages_restants == 0) {

                $stmt = $pdo->prepare("DELETE FROM discussion WHERE id = ?");
                $stmt->execute([$id_discussion]);

                // Stocke le message d'information dans une variable de la session de l'utilisateur pour pouvoir le récupérer sur une autre page
                $_SESSION['message'] = 
                    "<div class='alert alert-success text-center'>
                        Votre message a bien été supprimé !<br>
                        La discussion a également été supprimée car il s'agissait du dernier message de cette discussion !
                    </div>";

                // Redirection vers le forum si la discussion ne contient plus de message
                header("Location: forum.php");
		        exit;

            }

		}

        // Stocke le message d'information dans une variable de la session de l'utilisateur pour pouvoir le récupérer sur une autre page
		$_SESSION['message'] = "<div class='alert alert-success text-center'>Votre message a bien été supprimé !</div>";

        // Redirection vers la discussion si il reste encore au moins 1 message
		header("Location: discussion.php?id=" . $id_discussion);
		exit;
    
    }

?>