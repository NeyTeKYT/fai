<?php 

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
        else $error = "Identifiants incorrects. Vérifiez votre nom d'utilisateur et votre mot de passe.";

    }

    include($racine_path . "templates/login.php");  // Contient le formulaire

    if(isset($error)) echo "<h3 id='login-error' class='text-center text-danger'>$error</h3>";     // Place le message d'erreur en dessous du formulaire
    
    include($racine_path . "templates/footer.php");

?>