<?php 

    // Fonction qui supprime une discussion de la BDD
    function supprimer_discussion($id_discussion) {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("DELETE FROM discussion WHERE id = ?");
        return $stmt->execute([$id_discussion]);

    }

?>