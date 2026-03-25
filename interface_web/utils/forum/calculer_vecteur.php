<?php 

    // Fonction qui calcule le vecteur binaire d'un texte (titre ou message) en fonction du vocabulaire
    function calculer_vecteur($texte) {

        $texte_array = explode(" ", strtolower($texte));    // Tableau contenant chaque mot du texte à analyser en minuscule pour comparer avec le vocabulaire
        $nb_mots = count($texte_array);

        // Ouverture du vocabulaire
        $vocabulaire = fopen(__DIR__ . "/../../vocabulary.txt", "r+");
        // Ouvre le vocabulaire en mode lecture / écriture pour pouvoir ajouter des mots au vocabulaire si besoin et éviter de le fermer et le réouvrir pour actualiser 
        if(!$vocabulaire) exit;

        // Ouverture de la stop list
        $stop_list = fopen(__DIR__ . "/../../stop_list.txt", "r");
        if($stop_list) exit;

        // On fera plus tard une fonction "private" static de ce processus pour que la fonction calculer_vecteur soit plus courte et plus facilement lisible = encapsulation
        for($i = 0; $i < $nb_mots; $i++) {

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

            $is_in_vocabulary = false;
        
            // Regarde si le mot est déjà dans le vocabulaire ou pas encore pour pouvoir l'ajouter = apprentissage par renforcement
            while(($line = fgets($vocabulaire)) !== false) {

                $vocabulary_word = trim($line);

                if($vocabulary_word == $texte_array[$i]) {
                    $is_in_vocabulary
                    break;
                }

            }

            if($is_in_vocabulary) continue; // Ne traite pas un mot déjà présent dans le vocabulaire

            // Si on est ici, c'est que le mot est "pertinent" et non présent dans le vocabulaire
            _ajouter_mot_vocabulaire($vocabulaire, $texte_array($i));

        }

        fclose($stop_list);

        $vecteur = [];  // Vecteur binaire qui contiendra 1 à l'indice i si le mot du vocabulaire à la ligne i est présent dans le texte, 0 sinon.
        $ind = 0;

        while(($line = fgets($vocabulaire)) !== false) {    // Traitement du vocabulaire ligne par ligne

            $mot = trim($line);
            $vecteur[$ind] = 0;

            for($i = 0; $i < $nb_mots; $i++) {  // Traitement du tableau de mots pour le texte analysé

                // Met vecteur[$ind] à 1 si le texte contient le mot du vocabulaire
                if($mot == $texte_array[$i]) {
                    $vecteur[$ind] = 1;
                    break;  // Sort de la boucle for, mais pas de la boucle while
                }

            }

            $ind++;

        }

        fclose($vocabulaire);

        return $vecteur;

    }

?>