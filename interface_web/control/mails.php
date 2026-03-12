<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/db.php");

	include($racine_path . "templates/imap.php");

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

	if(!$imap) exit("Erreur lors de l'initiation de la connexion au serveur IMAP");

	if(isset($_POST['destinataire'])) {	// Cas d'envoi d'un mail

		// Stockage des informations dans des variables
		$destinataire = filter_var($_POST['destinataire'], FILTER_VALIDATE_EMAIL);
		$sujet = trim($_POST['sujet']);
		$message = trim($_POST['message']);

		if($destinataire && $sujet && $message) {

			// Additional headers
			$headers = "From: box@ceri.com\r\n" . 
			"To: $destinataire\r\n" .
			"Subject: $sujet\r\n" .
			"Content-Type: text/plain; charset=UTF-8\r\n" ;

			// Vérification de l'envoi du mail
			if(mail($destinataire, $sujet, $message, $headers)) echo "<div class='alert alert-success text-center'>Votre mail a bien été envoyé !</div>";
			else echo "<div class='alert alert-danger text-center'>Une erreur est survenue lors de l'envoi du mail !</div>";

			// Ajoute le mail dans le dossier "Sent" des mails envoyés
			$mail_to_save = $headers . $message;
			imap_append($imap_sent, $imap_fai . "Sent", $mail_to_save);

		}

	}

	if(isset($_POST['supprimer_recu'])) {	// Cas de suppression d'un mail reçu

		$id = (int) $_POST['supprimer_recu'];	// Stockage de l'ID du mail à supprimer

		// Suppression du mail
		if(imap_delete($imap, $id) && imap_expunge($imap)) echo "<div class='alert alert-success text-center'>Votre mail reçu a bien été supprimé !</div>";
		else echo "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la suppression du mail !</div>";

	}

	if(isset($_POST['toggle_lu'])) {	// Cas où un mail est marqué comme lu / non lu

		$id = (int) $_POST['toggle_lu'];	// Stockage de l'ID du mail à marquer comme lu / non lu

		$overview = imap_fetch_overview($imap, $id, 0);	// Récupération de la visibilité actuelle du mail (lu / non lu)

		// Modifie la visibilité avec l'autre valeur possible (lu => non lu / non lu => lu)
		if(!$overview[0]->seen) imap_setflag_full($imap, $id, "\\Seen");
		else imap_clearflag_full($imap, $id, "\\Seen");
		
	}

	$mails_recus = [];
	$emails = imap_search($imap, 'ALL');

	if($emails) {

		rsort($emails); // Ordonne les mails des plus récents aux plus anciens

		foreach($emails as $email_number) {

			$overview = imap_fetch_overview($imap, $email_number, 0);

			$mails_recus[] = [
				'id' => $email_number,
				'sujet' => $overview[0]->subject ?? '(Sans sujet)',
				'expediteur' => $overview[0]->from ?? '',
				'date' => $overview[0]->date ?? '',
				'lu' => $overview[0]->seen ? true : false
			];

		}

	}

	if($imap_sent) {	// Si la connexion au dossier "Sent" du serveur mail a bien été initialisé

		$mails_envoyes = [];
		$emails_sent = imap_search($imap_sent, 'ALL');

		if(isset($_POST['supprimer_envoye'])) {	// Cas de suppression d'un mail envoyé

			$id = (int) $_POST['supprimer_envoye'];	// Stockage de l'ID du mail à supprimer

			// Suppression du mail
			if(imap_delete($imap_sent, $id) && imap_expunge($imap_sent)) echo "<div class='alert alert-success text-center'>Votre mail envoyé a bien été supprimé !</div>";
			else echo "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la suppression du mail !</div>";

		}

		if($emails_sent) {

			rsort($emails_sent);	// Ordonne les mails des plus récents aux plus anciens

			foreach($emails_sent as $email_number) {

				$overview = imap_fetch_overview($imap_sent, $email_number, 0);

				$mails_envoyes[] = [
					'id' => $email_number,
					'sujet' => $overview[0]->subject ?? '(Sans sujet)',
					'destinataire' => $overview[0]->to ?? '',
					'date' => $overview[0]->date ?? ''
				];

			}
			
		}

		imap_close($imap_sent);

	}

	imap_close($imap);

    include($racine_path . "templates/mails.php");	// Contient le contenu spécifique de la boite mail

	include($racine_path . "templates/footer.php");	// Footer avec les informations du créateur
	
?>
