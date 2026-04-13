<?php 

    // Fonction qui récupère tous les messages stockées dans la BDD et les retourne
    function _recuperer_messages() {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT id, message FROM message");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

?>