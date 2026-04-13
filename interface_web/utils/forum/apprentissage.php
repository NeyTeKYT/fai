<?php 

    function _apprentissage($nb_mots, $stop_list, $texte_array, $vocabulaire) {

        for($i = 0; $i < $nb_mots; $i++) {

            rewind($stop_list); // remet le pointeur au début de la stop list à chaque nouveau mot, sinon on ne relit pas depuis le début

            $is_stop_word = false;

            // Regarde si le mot sur lequel on se trouve est dans la stop list = ne doit pas être traité et ajouté au vocabulaire
            while(($line = fgets($stop_list)) !== false) {

                $stop_word = trim($line);

                if($stop_word == $texte_array[$i]) {
                    $is_stop_word = true;
                    break;
                }

            }

            if($is_stop_word) continue; // Ne traite pas les stop words

            rewind($vocabulaire);

            $is_in_vocabulary = false;
        
            // Regarde si le mot est déjà dans le vocabulaire ou pas encore pour pouvoir l'ajouter = apprentissage par renforcement
            while(($line = fgets($vocabulaire)) !== false) {

                $vocabulary_word = trim($line);

                if($vocabulary_word == $texte_array[$i]) {
                    $is_in_vocabulary = true;
                    break;
                }

            }

            if($is_in_vocabulary) continue; // Ne traite pas un mot déjà présent dans le vocabulaire

            // Si on est ici, c'est que le mot est "pertinent" et non présent dans le vocabulaire
            _ajouter_mot_vocabulaire($vocabulaire, $texte_array[$i]);

        }

    }

?>