<?php 

    // Fonction qui récupère tous les titres stockées dans la BDD et les retourne
    function _recuperer_titres() {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT id, title FROM discussion");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

?>