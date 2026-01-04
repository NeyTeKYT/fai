<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include($racine_path . "templates/navbar.php");	// Barre de navigation

	// Création de variables avec des valeurs par défaut
	$download_speed = null;	// Vitesse de téléchargement
	$upload_speed = null;	// Vitesse de publication
	$test_duration = null;	// Durée totale de la mesure du débit
	$verdict_msg = null;	// Message pour situer l'utilisateur sur son utilisation possible d'Internet (dans le style de nombreux SpeedTest)
	// Messages d'avertissement
	$usage_msg = null;	
	$warning_msg = null;

	$file_size_bytes = 1024 * 1024 * 1024;	// Taille du fichier de test (1 Go)

	$iterations = 3;	// 3 itérations pour avoir une moyenne fiable sur la mesure du débit

	// Cas où l'utilisateur lance le diagnostic
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$global_start_time = microtime(true);	// Lance le chronomètre pour définir la durée totale du diagnostic.
		
		$tmp_file = "/tmp/test_1Go.bin";	// Chemin vers le fichier de test à envoyer vers le serveur FTP qui tourne sur le FAI

		// Création du fichier qui sera envoyé vers le FAI
		if(!file_exists($tmp_file)) exec("dd if=/dev/zero of=$tmp_file bs=1M count=1024 2>/dev/null");

		$download_results = [];
		$upload_results = [];

		for($i = 0; $i < $iterations; $i++) {

			// Gestion du débit descendant = récupération du fichier depuis le FAI : le fichier est envoyé par le FAI
			$start_time = microtime(true);
			exec("lftp -e 'get test_1Go.bin -o /tmp/received_1Go.bin; bye' ftp://stud:stud@fai.ceri.com 2>/dev/null");
			$end_time = microtime(true);
			$duration = $end_time - $start_time;
			if($duration > 0) $download_results[] = $file_size_bytes / $duration;

			// Gestion du débit montant = transfert du fichier vers le FAI : le fichier est envoyé par la box
			$start_time = microtime(true);
			exec("lftp -e 'put $tmp_file -o test_1Go.bin; bye' ftp://stud:stud@fai.ceri.com 2>/dev/null");
			$end_time = microtime(true);
			$duration = $end_time - $start_time;
			if($duration > 0) $upload_results[] = $file_size_bytes / $duration;

		}

		$global_end_time = microtime(true);	// Fin du chronomètre pour définir la durée totale du diagnostic
		$test_duration = round($global_end_time - $global_start_time);	// Calcule la durée totale du diagnostic

		// Calcule la moyenne du débit descendant = téléchargement du fichier depuis le FAI
		$download_speed = (array_sum($download_results) / count($download_results)) * 8 / 1000000;
		// Calcule la moyenne du débit montant = publication du fichier vers le FAI
		$upload_speed = (array_sum($upload_results) / count($upload_results)) * 8 / 1000000;

		$alerts = [];	// Tableau contenant le message pour le bandeau de notification

		// Cas où les résultats sont obtenus
		if($download_speed !== null && $upload_speed !== null) {

			// Affichage d'un message pour informer l'utilisateur de la qualité et du résultat de la connexion entre la box et le FAI
			if($download_speed < 50) {
				$quality_msg = "Votre connexion Internet est lente.";
				$verdict_msg = "La navigation peut être difficile et le streaming HD est limité.";
			}
			elseif($download_speed < 200) {
				$quality_msg = "Votre connexion Internet est correcte.";
				$verdict_msg = "Vous pouvez naviguer confortablement et regarder des vidéos en HD.";
			}
			elseif($download_speed < 1000) {
				$quality_msg = "Votre connexion Internet est très bonne.";
				$verdict_msg = "Vous pouvez regarder des vidéos 4K, jouer en ligne et utiliser plusieurs appareils simultanément.";
			}
			else {
				$quality_msg = "Votre connexion Internet est excellente.";
				$verdict_msg = "Tous les usages sont possibles sans limitation, y compris le streaming 4K et le télétravail intensif.";
			}

			if($upload_speed < 20 && $download_speed > 200) $warning_msg = "Attention : le débit montant est faible. Les visioconférences ou l’envoi de fichiers volumineux peuvent être impactés.";
			if($download_speed > 500 && $upload_speed < 20) $usage_msg = "Connexion rapide mais potentiellement peu confortable pour les usages interactifs.";

			$alerts[] = "<div class='alert alert-success text-center'><strong>Diagnostic terminé</strong><br>$quality_msg<br><span class='text-muted'>Durée du diagnostic : {$test_duration} secondes</span></div>";

		}

		else $alerts[] = "<div class='alert alert-success text-center'>Erreur lors de la mesure du débit.</div>";

		@unlink("/tmp/received_1Go.bin");	// Suppression du fichier récupéré depuis le FAI

	}

	include($racine_path . "templates/diagnostic.php");

	include($racine_path . "templates/footer.php");

?>
