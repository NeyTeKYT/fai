<?php 

    // Modifie la taille du VARCHAR "vecteur" dans les tables discussion et message
    // pour qu'elle corresponde exactement au nombre de mots du vocabulaire
    function _modifier_tailles_varchar() {

        global $pdo;

        $nouvelle_taille = _compter_vocabulaire();

        $tables = ['discussion', 'message'];

        foreach ($tables as $table) $pdo->exec("ALTER TABLE $table MODIFY vecteur VARCHAR($nouvelle_taille)");

    }

?>