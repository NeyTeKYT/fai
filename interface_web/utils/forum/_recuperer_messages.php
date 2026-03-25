<?php 

    // Fonction qui récupère tous les messages stockées dans la BDD et les retourne
    private function recuperer_messages() {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT message FROM message");
	    $stmt->execute();
	    return $stmt->fetchAll();

    }

?>