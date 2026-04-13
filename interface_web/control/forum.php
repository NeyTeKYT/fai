<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	require_once $racine_path . "templates/db.php";

	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once $racine_path . "utils/forum/recuperer_titres.php";
	require_once $racine_path . "utils/forum/recuperer_messages.php";
	require_once $racine_path . "utils/forum/mettre_a_jour_vecteur.php";
	require_once $racine_path . "utils/forum/compter_vocabulaire.php";
	require_once $racine_path . "utils/forum/construire_vecteur.php";
	require_once $racine_path . "utils/forum/modifier_tailles_varchar.php";
	require_once $racine_path . "utils/forum/calculer_similarite.php";
	require_once $racine_path . "utils/forum/resynchroniser_vecteurs.php";
	require_once $racine_path . "utils/forum/ajouter_mot_vocabulaire.php";
	require_once $racine_path . "utils/forum/apprentissage.php";
	require_once $racine_path . "utils/forum/calculer_vecteur.php";
	require_once $racine_path . "utils/forum/verifier_ia.php";
	require_once $racine_path . "utils/forum/creer_discussion.php";
	require_once $racine_path . "utils/forum/recuperer_discussions.php";
	require_once $racine_path . "utils/forum/recuperer_username.php";
	require_once $racine_path . "utils/forum/recuperer_date.php";
	require_once $racine_path . "utils/forum/interpreter_similarite.php";
	require_once $racine_path . "utils/discussion/supprimer_discussion.php";

	require_once $racine_path . "utils/discussion/supprimer_discussion.php";	// Fonction qui supprime une discussion de la BDD

	$id_utilisateur = $_SESSION['id'];	// Stockage dans une variable de l'ID de l'utilisateur connecté

	// Récupération du rôle de l'utilisateur connecté
	$stmt = $pdo->prepare("SELECT role FROM user WHERE id = ?");
	$stmt->execute([$id_utilisateur]);
	$role_utilisateur = $stmt->fetchColumn();

	// Variables globales pour l'affichage des résultats de l'IA dans le template
	$resultats_ia = [];
	$type_ia = null;

	_modifier_tailles_varchar();  // Ajuste le VARCHAR à la taille actuelle du vocabulaire
	_resynchroniser_vecteurs();   // Calcule les vecteurs manquants

	// N'autorise pas un technicien à ouvrir une discussion = il répond aux problèmes des utilisateurs 
	if($_SERVER['REQUEST_METHOD'] === 'POST' && $role_utilisateur !== 'technicien') {

		// Algorithme de traitement de chaines de caractères sur le titre
		if(isset($_POST['titre_ia'])) {

			$resultats_ia = ia($_POST['titre'], "discussion");
			$type_ia = "discussion";

		}
		
		// Algorithme de traitement de chaines de caractères sur le message
		elseif(isset($_POST['message_ia'])) {

			$resultats_ia = ia($_POST['message'], "message");
			$type_ia = "message";

		}

		// Création d'une discussion 
		elseif(isset($_POST['creer_discussion'])) creer_discussion($_POST['titre'], $_POST['message'], $id_utilisateur);

	}

	$discussions = recuperer_discussions();

	require_once $racine_path . "templates/head.php";	// La balise <head> avec toutes les métadonnées 
	require_once $racine_path . "templates/navbar.php";	// Barre de navigation pour se déplacer entre les pages

	// Vérifie si une action a été effectuée par l'utilisateur 
	if(isset($_SESSION['message'])) {
		echo $_SESSION['message'];
		unset($_SESSION['message']);	// Supprime le message pour qu'il ne soit diffusé qu'une seule fois
	}

    require_once $racine_path . "templates/forum/forum.php";	// Contient le contenu spécifique de la page d'accueil du forum
	require_once $racine_path . "templates/footer.php";	// Footer contenant les informations sur le créateur
	
?>
