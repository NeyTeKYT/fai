<?php 

    function _compter_vocabulaire() {

        $vocabulaire = fopen(__DIR__ . "/../../vocabulary.txt", "r");
        if(!$vocabulaire) exit("Impossible d'ouvrir le vocabulaire pour le compter");

        $nb_mots = 0;
        while(fgets($vocabulaire) !== false) $nb_mots++;

        fclose($vocabulaire);
        return $nb_mots;

    }

?>