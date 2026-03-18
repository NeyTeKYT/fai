<?php 

    // Fonction qui retourne le créateur d'une discussion à partir de l'ID
    function recuperer_username($id) {

        global $pdo;	// Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT user.username AS createur FROM user INNER JOIN `discussion` ON user.id = discussion.creator WHERE discussion.id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['createur'];

    }

?>