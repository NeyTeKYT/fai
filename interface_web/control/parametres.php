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

	include($racine_path . "templates/db.php");

    // Récupération des informations de l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT username, mode, role FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch();

    $success = $error = null;   // Initialise deux variables pour le résultat de la modification des paramètres de l'utilisateur

    // Fonction qui valide ou non le mot de passe modifié de l'utilisateur
    function isPasswordValid($password) {
        return strlen($password) >= 12 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[0-9]/', $password) && preg_match('/[\W]/', $password);
    }

    // Cas d'envoi du formulaire pour modifier les paramètres de l'utilisateur via une requête POST
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Stockage des paramètres envoyés dans des variables pour pouvoir plus facilement les manipuler
        $new_username = trim($_POST['username']);
        $new_password = $_POST['password'];
        $mode = ($user['role'] === 'technicien') ? null : ($_POST['mode'] ?? $user['mode']);    // Un technicien ne peut pas modifier son mode de configuration de la box

        // Vérifie que le nom d'utilisateur n'est pas déjà utilisé dans la BDD 
        if($new_username !== $user['username']) {
            $stmt_check = $pdo->prepare("SELECT id FROM user WHERE username = ?");
            $stmt_check->execute([$new_username]);
            if($stmt_check->fetch()) $error = "Ce nom d'utilisateur indisponible.";
        }

        // Validation mot de passe
        if(!$error && !empty($new_password) && !isPasswordValid($new_password)) $error = "Le mot de passe doit contenir au moins 12 caractères, une majuscule, un chiffre et un caractère spécial.";

        // Si aucune erreur n'a été détectée
        if(!$error) {

            // Lorsque l'on est technicien on ne modifie que le nom d'utilisateur
            if($user['role'] === 'technicien') {
                $stmt_update = $pdo->prepare("UPDATE user SET username = ? WHERE id = ?");
                $stmt_update->execute([$new_username, $_SESSION['id']]);
            }

            // Cas où l'utilisateur connecté est un client du FAI = a une box à administrer
            else {
                $stmt_update = $pdo->prepare("UPDATE user SET username = ?, mode = ? WHERE id = ?");
                $stmt_update->execute([$new_username, $mode, $_SESSION['id']]);
            }
            
            // Cas où l'utilisateur souhaite modifier son mot de passe
            if(!empty($new_password)) {
                $hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $stmt_pwd = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
                $stmt_pwd->execute([$hashed, $_SESSION['id']]);
            }

            $_SESSION['mode'] = $mode;  // Change le nouveau mode de la session

            $success = "Paramètres mis à jour avec succès.";
        }
    }

    include($racine_path . "templates/parametres.php"); // Contient le contenu spécifique de la page des paramètres

    include($racine_path . "templates/footer.php"); // Footer avec les informations du créateur
