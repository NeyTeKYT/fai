<?php

    // Resynchronise tous les vecteurs de la BDD avec le vocabulaire actuel
    function _resynchroniser_vecteurs() {

        $discussions = _recuperer_titres();
        foreach($discussions as $discussion) {
            $nouveau_vecteur_chaine = implode('', _construire_vecteur($discussion['title']));
            _mettre_a_jour_vecteur("discussion", $discussion['id'], $nouveau_vecteur_chaine);
        }

        $messages = _recuperer_messages();
        foreach($messages as $message) {
            $nouveau_vecteur_chaine = implode('', _construire_vecteur($message['message']));
            _mettre_a_jour_vecteur('message', $message['id'], $nouveau_vecteur_chaine);
        }

    }

?>