<?php 

    // Fonction qui ajoute un mot au vocabulaire et actualise tous les one hot vectors de la BDD
    function _ajouter_mot_vocabulaire($vocabulaire, $mot) {

        fseek($vocabulaire, 0, SEEK_END);   // Le nouveau mot sera placé à la fin du vocabulaire (pour avoir aussi un historique)
        fwrite($vocabulaire, $mot . "\n");

        global $pdo; // Permet d'accéder à la variable globale $pdo

        $discussions = _recuperer_titres();    // Récupère tous les titres de la BDD
        $messages = _recuperer_messages();  // Récupère tous les messages de la BDD

        // Modifier la taille du VARCHAR des deux tables avant d'insérer le nouveau one hot vector
        _modifier_tailles_varchar();

        _resynchroniser_vecteurs();

    } 

?>