<?php 

    // Fonction qui retourne la date de création d'une discussion à partir de l'ID
    function recuperer_date($id) {

        global $pdo;	// Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("
            SELECT message.date FROM message INNER JOIN `discussion` ON message.discussion = discussion.id 
            WHERE discussion.id = ? ORDER BY discussion.id ASC LIMIT 1");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['date'];

    }

?>