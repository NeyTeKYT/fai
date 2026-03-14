<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/db.php");

	include($racine_path . "utils/forum/creer_discussion.php");	// Fonction qui ajoute une discussion (titre + message) dans la BDD
	include($racine_path . "utils/forum/recuperer_discussions.php");	// Fonction qui retourne toutes les discussions existantes dans la BDD

	// Récupération du rôle de l'utilisateur connecté
	$id_utilisateur = $_SESSION['id'];
	$stmt = $pdo->prepare("SELECT role FROM user WHERE id = ?");
	$stmt->execute([$id_utilisateur]);
	$role_utilisateur = $stmt->fetchColumn();

	// N'autorise pas un technicien à ouvrir une discussion = il répond aux problèmes des utilisateurs 
	if($_SERVER['REQUEST_METHOD'] === 'POST' && $role_utilisateur !== 'technicien') {

		if(isset($_POST['titre_ia'])) {

			$titre = trim($_POST['titre']);	// Titre de la discussion
			// Lance l'algorithme 

		}
		
		elseif(isset($_POST['message_ia'])) {

			$message = trim($_POST['message']);	// Message envoyé
			// Lance l'algorithme

		}

		elseif(isset($_POST['creer_discussion'])) {

			$titre = trim($_POST['titre']);	// Titre de la discussion
			$message = trim($_POST['message']);	// Message envoyé

			if(($titre !== "") && ($message !== "")) creer_discussion($titre, $message, $id_utilisateur);

		}

	}

	$discussions = recuperer_discussions();

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 
	include($racine_path . "templates/navbar.php");	// Barre de navigation pour se déplacer entre les pages
    include($racine_path . "templates/forum.php");	// Contient le contenu spécifique de la page d'accueil du forum
	include($racine_path . "templates/footer.php");	// Footer contenant les informations sur le créateur
	
?>
