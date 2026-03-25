<?php 

    // Fonction qui ajoute un mot au vocabulaire et actualise tous les one hot vectors de la BDD
    private function _ajouter_mot_vocabulaire($vocabulaire, $mot) {

        // Dois-je réouvrir le fichier ou est-ce que je peux le passer en paramètre comme ici et utilisateur le descripteur de fichier déjà ouvert
        if($vocabulaire) fwrite($vocabulaire, $mot);

        global $pdo; // Permet d'accéder à la variable globale $pdo

        $discussions = _recuperer_titres();    // Récupère tous les titres de la BDD
        $messages = _recuperer_messages();  // Récupère tous les messages de la BDD

        // Modifier la taille du VARCHAR des deux tables avant d'insérer le nouveau one hot vector
        _modifier_tailles_varchar();

        // Calculer le nouveau one hot vector pour chaque enregistrement de la BDD et le stocker 
        // Utiliser la/les fonction(s) déjà implémentées

    } 

?>