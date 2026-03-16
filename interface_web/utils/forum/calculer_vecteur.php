<?php 

    // Fonction qui calcule le vecteur binaire d'un texte (titre ou message) en fonction du vocabulaire
    function calculer_vecteur($texte) {

        $fichier = fopen(__DIR__ . "/../../vocabulary.txt", "r");

        // Tableau contenant chaque mot du texte à analyser en minuscule pour comparer avec le vocabulaire
        $texte = preg_replace('/[^a-z0-9 ]/i', '', strtolower($texte));
        $texte_array = explode(" ", $texte);
        $nb_mots = count($texte_array);

        if($fichier) {

            // Vecteur binaire qui contiendra 1 à l'indice i si le mot du vocabulaire à la ligne i est présent dans le texte, 0 sinon.
            $vecteur = [];
            $ind = 0;

            while(($line = fgets($fichier)) !== false) {    // Traitement du vocabulaire ligne par ligne

                $mot = trim($line);
                $vecteur[$ind] = 0;

                for($i = 0; $i < $nb_mots; $i++) {  // Traitement du tableau de mots pour le texte analysé

                    // Met vecteur[$ind] à 1 si le texte contient le mot du vocabulaire
                    if($mot == $texte_array[$i]) {
                        $vecteur[$ind] = 1;
                        break;
                    }

                }

                $ind++;

            }

            fclose($fichier);

            for($i = 0; $i < $nb_mots; $i++) echo $vecteur[$i];

        }

    }

?>