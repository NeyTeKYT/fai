<?php 

    session_start();

    $racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

    include($racine_path . "templates/db.php");

    // Soumission du formulaire de connexion
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Récupération des informations et stockage dans des variables
        $username = $_POST['username']; // Nom d'utilisateur
        $password = $_POST['password']; // Mot de passe

        // Récupération d'un utilisateur existant dans la BDD ayant ce nom d'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Si un utilisateur a été trouvé et que le hash du mot de passe entré par l'utilisateur correspond à celui stocké dans la BDD
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];  // Ajout de l'ID de session avec la valeur de l'ID de l'utilisateur 
            header("Location: ../index.php");
            exit;
        } 

        else $alert = "<div class='alert alert-danger text-center'>Identifiants incorrects !</div>";   // Texte pour le bandeau de notification

    }

    include($racine_path . "templates/login.php");  // Contient le formulaire de connexion
    
    include($racine_path . "templates/footer.php"); // Footer avec les informations du créateur

?>