<?php 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    $racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

    include($racine_path . "templates/db.php");

    // Vérifie les identifiants entrés par l'utilisateur
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            header("Location: ../index.php");
            exit;
        } 
        else $error = "Identifiants incorrects.";

    }

    if(isset($error)) echo "<p>$error</p>"; 

    include($racine_path . "templates/login.php"); // contient le formulaire
    
    include($racine_path . "templates/footer.php");

?>