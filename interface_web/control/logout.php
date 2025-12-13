<?php

    session_start();

    // Destruction de toutes les variables de session
    $_SESSION = [];

    // Destruction de la session côté serveur
    session_destroy();

    // Redirige vers le formulaire de connexion
    header("Location: login.php");
    exit;

?>