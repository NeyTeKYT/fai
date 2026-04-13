<?php 

    // Fonction qui calcule le vecteur binaire d'un texte (titre ou message) en fonction du vocabulaire
    function _calculer_vecteur($texte) {

        $texte_nettoye = preg_replace('/[^a-zA-ZÀ-ÿ\s]/', '', strtolower($texte));  // Enlève la ponctuation
        $texte_array = explode(" ", strtolower($texte_nettoye));    // Tableau contenant chaque mot du texte à analyser en minuscule pour comparer avec le vocabulaire
        $nb_mots = count($texte_array);

        // Ouverture du vocabulaire
        $vocabulaire = fopen(__DIR__ . "/../../vocabulary.txt", "r+");
        // Ouvre le vocabulaire en mode lecture / écriture pour pouvoir ajouter des mots au vocabulaire si besoin et éviter de le fermer et le réouvrir pour actualiser 
        if(!$vocabulaire) exit;

        // Ouverture de la stop list
        $stop_list = fopen(__DIR__ . "/../../stop_list.txt", "r");
        if(!$stop_list) exit;

        _apprentissage($nb_mots, $stop_list, $texte_array, $vocabulaire);      

        fclose($stop_list);

        rewind($vocabulaire);

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