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

	// État du DNS
	$dns_state = trim(shell_exec("systemctl is-active bind9 2>/dev/null"));	
	if($dns_state == "active") $dns_state_span = "<span class='text-success fw-bolder'>$dns_state</span>";
	else $dns_state_span = "<span class='text-danger fw-bolder'>$dns_state</span>";

	// Nom de domaine configuré
	$current_first_name = trim(shell_exec("cat /etc/bind/named.conf.local | grep 'zone' | grep 'ceri.com' | cut -d ' ' -f 2 | cut -d '.' -f 1 | cut -d '\"' -f 2"));
	$dns_domain = $current_first_name . ".ceri.com";

	// Récupère le prénom
	$current_first_name = `cat /etc/bind/named.conf.local | grep "zone" | grep "ceri.com" | cut -d " " -f 2 | cut -d "." -f 1 | cut -d '"' -f 2`;

	// Cas d'envoi du formulaire
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$first_name = trim($_POST['first_name']);

		if(empty($first_name)) echo "<h2 class='resultat red'>Le prénom ne peut pas être vide !</h2>";
		# Un prénom ne peut pas contenir de chiffres et de caractères spéciaux
		else if(!preg_match('/^[a-zA-Z-]+$/', $first_name)) echo "<h2 class='resultat red'>Le prénom ne doit contenir que des lettres et des tirets !</h2>";
		# Taille maximum d'un préfixe DNS
		else if(strlen($first_name) > 63) echo "<h2 class='resultat red'>Le prénom doit avoir une longueur inférieure à 63 caractères !</h2>";
		else if(file_exists("/etc/bind/db.$first_name.ceri.com")) echo "<h2 class='resultat red'>Le prénom a déjà été configuré comme domaine !</h2>";
		
		else {
			# Met le prénom en minuscule : Florent -> florent pour ne pas ping Florent.ceri.com
			$first_name_as_dns_prefix = strtolower($first_name);

			$script_command = "sudo /home/stud/scripts/dns.sh " . escapeshellarg($first_name_as_dns_prefix);
			shell_exec($script_command);
			$current_first_name = $first_name_as_dns_prefix;
			echo "<h2 class='resultat green'>Le nouveau domaine de la box Internet est $current_first_name.ceri.com !</h2>";
		}

	}

    include($racine_path . "templates/formulaire_dns.php");

	include($racine_path . "templates/footer.php");
	
?>
