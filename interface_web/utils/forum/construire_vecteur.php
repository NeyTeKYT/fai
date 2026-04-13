<?php 

    function _construire_vecteur($texte) {

        $texte_array = explode(" ", strtolower($texte));
        $nb_mots = count($texte_array);

        $vocabulaire = fopen(__DIR__ . "/../../vocabulary.txt", "r");
        if(!$vocabulaire) exit("Impossible d'ouvrir le vocabulaire");

        $vecteur = [];
        $ind = 0;

        while(($line = fgets($vocabulaire)) !== false) {
            $mot = trim($line);
            $vecteur[$ind] = 0;

            for($i = 0; $i < $nb_mots; $i++) {
                if($mot === $texte_array[$i]) {
                    $vecteur[$ind] = 1;
                    break;
                }
            }

            $ind++;
        }

        fclose($vocabulaire);
        return $vecteur;

    }

?>