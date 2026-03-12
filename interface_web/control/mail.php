<?php

	// Vérifie si l'utilisateur est connecté
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

	// Vérifie qu'un ID a bien été passé en paramètre de l'URL pour récupérer le mail
	if(!isset($_GET['type']) || !isset($_GET['id'])) {
		echo "<div class='alert alert-danger text-center'>Mail introuvable</div>";
		exit;
	}

	// Stockage des paramètres de l'URL
	$type = trim($_GET['type']);	// Stockage du type de mail à récupérer (mail envoyé ou mail reçu)
	$id = (int) $_GET['id'];	// Stockage de l'ID du mail à récupérer

	if($type == 'envoye') $overview = imap_fetch_overview($imap_sent, $id, 0);	// Récupération des infos générales sur le mail envoyé
	elseif($type == 'recu') $overview = imap_fetch_overview($imap, $id, 0);	// Récupération des infos générales sur le mail reçu
	else {
		echo "<div class='alert alert-danger text-center'>Mail introuvable</div>";
		exit;
	}

	// Si les informations n'ont pas bien été récupérées
	if(!$overview) {
		echo "<div class='alert alert-danger text-center'>Mail introuvable</div>";
		exit;
	}

	// Variable contenant les informations utilisées pour l'affichage sur la template
	if($type == 'envoye') {
		$mail = [
			'id' => $id,
			'sujet' => $overview[0]->subject ?? '(Sans sujet)',
			'destinataire' => $overview[0]->to ?? '',
			'date' => $overview[0]->date ?? ''
		];
	}
	elseif($type == 'recu') {
		$mail = [
			'id' => $id,
			'sujet' => $overview[0]->subject ?? '(Sans sujet)',
			'expediteur' => $overview[0]->from ?? '',
			'date' => $overview[0]->date ?? ''
		];
	}

	// Récupération du message
	if($type == 'envoye') {
		$message = imap_fetchbody($imap_sent, $id, "1");
		if(!$message) $message = imap_fetchbody($imap_sent, $id, "1.1");
	}
	elseif($type == 'recu') {
		$message = imap_fetchbody($imap, $id, "1");
		if(!$message) $message = imap_fetchbody($imap, $id, "1.1");
	}

	$mail['message'] = $message;

	imap_setflag_full($imap, $id, "\\Seen");	// Marquer comme lu un mail sur lequel on a cliqué

	imap_close($imap);
	imap_close($imap_sent);

	include($racine_path . "templates/mail.php");	// Contient le contenu spécifique de l'affichage d'un mail

	include($racine_path . "templates/footer.php");	// Footer avec les informations du créateur

?>