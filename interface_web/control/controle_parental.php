<?php

    session_start();
    file_put_contents('/var/www/html/interface_web/templates/debug.log', 'PHP peut écrire dans /tmp - ' . date('H:i:s') . "\n");
    if(!isset($_SESSION['id'])) {
        header("Location: ./login.php");
        exit;
    }

    $racine_path = "../";

	require $racine_path . "templates/db.php";
    require $racine_path . "utils/securite/ia_controle_parental.php";
    require $racine_path . "utils/securite/gestion_profil.php";
    require $racine_path . "utils/securite/controle_parental.php";
	
    require $racine_path . "templates/head.php";
    require $racine_path . "templates/navbar.php";
    require $racine_path . "templates/controle_parental/controle_parental.php";
    require $racine_path . "templates/footer.php";

?>