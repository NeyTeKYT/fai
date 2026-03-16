<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	require $racine_path . "templates/db.php";

	require $racine_path . "utils/discussion/supprimer_discussion.php";	// Fonction qui supprime une discussion de la BDD
	require $racine_path . "utils/forum/creer_discussion.php";	// Fonction qui ajoute une discussion (titre + message) dans la BDD
	require $racine_path . "utils/forum/recuperer_discussions.php";	// Fonction qui retourne toutes les discussions existantes dans la BDD
	require $racine_path . "utils/forum/calculer_vecteur.php";	// Fonction qui calcule le vecteur binaire d'un texte (titre ou message) en fonction du vocabulaire

	$id_utilisateur = $_SESSION['id'];	// Stockage dans une variable de l'ID de l'utilisateur connecté

	// Récupération du rôle de l'utilisateur connecté
	$stmt = $pdo->prepare("SELECT role FROM user WHERE id = ?");
	$stmt->execute([$id_utilisateur]);
	$role_utilisateur = $stmt->fetchColumn();

	// N'autorise pas un technicien à ouvrir une discussion = il répond aux problèmes des utilisateurs 
	if($_SERVER['REQUEST_METHOD'] === 'POST' && $role_utilisateur !== 'technicien') {

		// Algorithme de traitement de chaines de caractères sur le titre
		if(isset($_POST['titre_ia'])) {

			calculer_vecteur($_POST['titre_ia']);

		}
		
		// Algorithme de traitement de chaines de caractères sur le message
		elseif(isset($_POST['message_ia'])) {

			// Lance l'algorithme

		}

		// Création d'une discussion 
		elseif(isset($_POST['creer_discussion'])) creer_discussion($_POST['titre'], $_POST['message'], $id_utilisateur);

	}

	$discussions = recuperer_discussions();

	require $racine_path . "templates/head.php";	// La balise <head> avec toutes les métadonnées 
	require $racine_path . "templates/navbar.php";	// Barre de navigation pour se déplacer entre les pages

	// Vérifie si une action a été effectuée par l'utilisateur 
	if(isset($_SESSION['message'])) {
		echo $_SESSION['message'];
		unset($_SESSION['message']);	// Supprime le message pour qu'il ne soit diffusé qu'une seule fois
	}

    require $racine_path . "templates/forum/forum.php";	// Contient le contenu spécifique de la page d'accueil du forum
	require $racine_path . "templates/footer.php";	// Footer contenant les informations sur le créateur
	
?>
