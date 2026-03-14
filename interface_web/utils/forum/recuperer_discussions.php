<?php 

    // Fonction qui retourne toutes les discussions existantes sur le forum
    function recuperer_discussions() {

        global $pdo;	// Permet d'accéder à la variable globale $pdo

        // Récupération de toutes les discussions dans un tableau
        $stmt = $pdo->query("
            SELECT discussion.id, discussion.title, user.username AS createur FROM discussion 
            INNER JOIN `user` ON discussion.creator = user.id ORDER BY discussion.id DESC");
        $discussions = $stmt->fetchAll();	// Tableau contenant toutes les discussions

        foreach($discussions as &$discussion) {

            // Récupération de la date de création de la discussion
            $stmt = $pdo->prepare("SELECT date FROM message WHERE discussion = ? ORDER BY id ASC LIMIT 1");
            $stmt->execute([$discussion['id']]);
            $date_creation_discussion = $stmt->fetch();

            // Ajout de la date récupérée comme valeur de $discussion
            $discussion['date_creation'] = $date_creation_discussion ? $date_creation_discussion['date'] : null;

            // Récupération du dernier message publié dans la discussion et son utilisateur
            $stmt = $pdo->prepare("
                SELECT message.message, message.date, user.username FROM message 
                INNER JOIN `user` ON message.user = user.id WHERE message.discussion = ?
                ORDER BY message.id DESC LIMIT 1");
            $stmt->execute([$discussion['id']]);
            $dernier_message = $stmt->fetch();

            $discussion['dernier_message'] = $dernier_message['message'] ?? '';
            $discussion['date_dernier_message'] = $dernier_message['date'] ?? '';
            $discussion['dernier_auteur'] = $dernier_message['username'] ?? '';

        }

        return $discussions;

    }

?>