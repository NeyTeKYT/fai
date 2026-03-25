<?php 

    // Fonction qui récupère tous les titres stockées dans la BDD et les retourne
    private function recuperer_titres() {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT title FROM discussion");
	    $stmt->execute();
	    return $stmt->fetchAll();

    }

?>