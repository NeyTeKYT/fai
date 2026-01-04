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

	// Récupération de l'état du serveur DNS
	$dns_state = trim(shell_exec("systemctl is-active bind9 2>/dev/null"));
	if($dns_state == "active") $dns_state_span = "<span class='text-success fw-bolder'>actif</span>";
	else $dns_state_span = "<span class='text-danger fw-bolder'>innactif</span>";

	// Détermine le nom de domaine actuellement configuré de la box Internet
	$current_first_name = trim(shell_exec("cat /etc/bind/named.conf.local | grep 'zone' | grep 'ceri.com' | cut -d ' ' -f 2 | cut -d '.' -f 1 | cut -d '\"' -f 2"));
	$dns_domain = $current_first_name . ".ceri.com";

	// Soumission d'un formulaire
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$alerts = [];	// Stockage des messages du bandeau de notification dans un tableau

		// Soumission du formulaire pour modifier le nom de domaine de la box Internet
		if(!empty($_POST['first_name'])) {

			$first_name = trim($_POST['first_name']);	// Stockage du prénom entré à la soumission du formulaire pour modifier le prénom (alias) de la box Internet

			// Un prénom ne peut pas contenir de chiffres et de caractères spéciaux
			if(!preg_match('/^[a-zA-Z-]+$/', $first_name)) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom ne doit contenir que des lettres et des tirets !</div>";

			// Vérifie que le prénom ne dépasse pas la taille maximum d'un nom de domaine DNS (63)
			else if(strlen($first_name) > 63) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom doit avoir une longueur inférieure à 63 caractères !</div>";
			
			// Gestion du cas où le prénom est le prénom déjà configuré = soumission du formulaire sans rien modifier
			else if(file_exists("/etc/bind/db.$first_name.ceri.com")) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom a déjà été configuré comme domaine !</div>";

			else {

				$first_name_as_dns_prefix = strtolower($first_name);	// L'alias doit être en minuscule

				// Exécution du script Bash avec en argument le nouveau prénom à configurer
				$script_command = "sudo /home/stud/scripts/dns.sh " . escapeshellarg($first_name_as_dns_prefix);
				shell_exec($script_command);

				$current_first_name = $first_name_as_dns_prefix;	// Mise à jour du prénom actuellement configuré
				$dns_domain = $current_first_name . ".ceri.com";	// Mise à jour du nom de domaine de la box Internet

				// Message de configuration différent en fonction du mode de configuration de l'utilisateur
				if($_SESSION['mode'] === 'debutant') $alerts[] = "<div class='alert alert-success text-center'>Le nouveau nom de la box Internet est $current_first_name.ceri.com !</div>";
				else $alerts[] = "<div class='alert alert-success text-center'>Le nouveau nom de domaine de la box Internet est $current_first_name.ceri.com !</div>";
				
			}

		}

		// Cas où l'utilisateur utilise le mode de configuration avancé et souhaite ajouter un sous-domaine
		if($_SESSION['mode'] === 'avance' && !empty($_POST['hostname'])) {

			// Stockage des valeurs envoyés via la requête POST
			$hostname = $_POST['hostname'];	// Alias 
			$ip = $_POST['ip'];	// Adresse IP

			// Un prénom ne peut pas contenir de chiffres et de caractères spéciaux
			if(!preg_match('/^[a-zA-Z-]+$/', $hostname)) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom ne doit contenir que des lettres et des tirets !</div>";

			// Vérifie que le prénom ne dépasse pas la taille maximum d'un nom de domaine DNS (63)
			else if(strlen($hostname) > 63) $alerts[] = "<div class='alert alert-danger text-center'>Le prénom doit avoir une longueur inférieure à 63 caractères !</div>";

			// Vérifie que l'adresse IP fournie est bien une adresse IPv4
			else if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) $alerts[] = "<div class='alert alert-danger text-center'>L'adresse IP fournie n'est pas une adresse IPv4 !</div>";

			// Vérifie que l'adresse IP n'est pas une adresse privée
			else if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) $alerts[] = "<div class='alert alert-danger text-center'>L'adresse IP doit être une adresse IPv4 privée !</div>";

			else {

				$hostname_as_dns_prefix = strtolower($hostname);	// L'alias doit être en minuscule

				// Exécution du script Bash avec les 3 arguments récupérés
				$script_command = "sudo /home/stud/scripts/dns_add_host.sh " . escapeshellarg($hostname) . " " . escapeshellarg($dns_domain) . " " . escapeshellarg($ip);
				shell_exec($script_command);

				// Message de configuration différent en fonction du mode de configuration de l'utilisateur
				if($_SESSION['mode'] === 'debutant') $alerts[] = "<div class='alert alert-success text-center'>Le nom $hostname_as_dns_prefix.$dns_domain a bien été configuré pour la machine ayant l'adresse $ip !</div>";
				else $alerts[] = "<div class='alert alert-success text-center'>Le sous-domaine $hostname_as_dns_prefix.$dns_domain a bien été configuré pour la machine ayant l'adresse IP $ip !</div>";

			}
		}

		// Cas où l'utilisateur utilise le mode de configuration avancé et souhaite supprimer un sous-domaine
		if($_SESSION['mode'] === 'avance' && isset($_POST['delete_host'])) {

			// Stockage du sous-domaine à supprimer
			$host = $_POST['delete_host'];

			$script_command = "sudo /home/stud/scripts/dns_delete_host.sh " . escapeshellarg($host) . " " . escapeshellarg($dns_domain);
			shell_exec($script_command);

			// Message de configuration différent en fonction du mode de configuration de l'utilisateur
			if($_SESSION['mode'] === 'debutant') $alerts[] = "<div class='alert alert-success text-center'>Le nom $host.$dns_domain correspondant à l'appareil ayant pour adresse $ip a bien été supprimé !</div>";
			else $alerts[] = "<div class='alert alert-success text-center'>Le sous-domaine $host.$dns_domain correspondant à l'appareil ayant pour adresse IP $ip a bien été supprimé !</div>";

		}
	}

	include($racine_path . "templates/formulaire_dns.php");		// Contient le contenu de la page

	include($racine_path . "templates/footer.php");		// Footer avec les informations du créateur

?>