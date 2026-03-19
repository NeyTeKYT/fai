<?php 

    // Fonction qui récupère tous les messages d'une discussion et les retourne
    function recuperer_messages($id_discussion) {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("
            SELECT message.id, message.message, message.date, user.username, user.id AS user_id, user.role FROM message
            INNER JOIN user ON message.user = user.id
            WHERE message.discussion = ?
            ORDER BY message.id ASC");
	    $stmt->execute([$id_discussion]);
	    return $stmt->fetchAll();

    }

?>