<?php 

    function _mettre_a_jour_vecteur($table, $id, $nouveau_vecteur_chaine) {

        global $pdo;

        $tables_autorisees = ['discussion', 'message'];
        if(!in_array($table, $tables_autorisees)) exit();

        $stmt = $pdo->prepare("UPDATE $table SET vecteur = :vecteur WHERE id = :id");
        $stmt->execute([
            ':vecteur' => $nouveau_vecteur_chaine,
            ':id'      => $id
        ]);

    }

?>