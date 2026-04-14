<?php 

    // Récupère tous les titres stockées dans la BDD et les retourne
    function _recuperer_titres() {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT id, title FROM discussion");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    // Récupère tous les messages stockées dans la BDD et les retourne
    function _recuperer_messages() {

        global $pdo;    // Permet d'accéder à la variable globale $pdo

        $stmt = $pdo->prepare("SELECT id, message FROM message");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    function _compter_vocabulaire() {

        $vocabulaire = fopen(__DIR__ . "/../../vocabulary.txt", "r");
        if(!$vocabulaire) exit("Impossible d'ouvrir le vocabulaire pour le compter");

        $nb_mots = 0;
        while(fgets($vocabulaire) !== false) $nb_mots++;

        fclose($vocabulaire);
        return $nb_mots;

    }

    // Modifie la taille du VARCHAR dans les tables discussion et message pour correspondre exactement au nombre de mots du vocabulaire
    function _modifier_tailles_varchar() {

        global $pdo;

        $nouvelle_taille = _compter_vocabulaire();
        $tables = ['discussion', 'message'];
        foreach ($tables as $table) $pdo->exec("ALTER TABLE $table MODIFY vecteur VARCHAR($nouvelle_taille)");

    }

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

    function _mettre_a_jour_vecteur($table, $id, $nouveau_vecteur_chaine) {

        global $pdo;

        $tables_autorisees = ['discussion', 'message'];
        if(!in_array($table, $tables_autorisees)) exit();

        $stmt = $pdo->prepare("UPDATE $table SET vecteur = :vecteur WHERE id = :id");
        $stmt->execute([':vecteur' => $nouveau_vecteur_chaine, ':id' => $id]);

    }

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

    // Ajoute un mot au vocabulaire et actualise tous les one hot vectors de la BDD
    function _ajouter_mot_vocabulaire($vocabulaire, $mot) {

        fseek($vocabulaire, 0, SEEK_END);   // Le nouveau mot sera placé à la fin du vocabulaire (pour avoir aussi un historique)
        fwrite($vocabulaire, $mot . "\n");

        global $pdo; // Permet d'accéder à la variable globale $pdo

        $discussions = _recuperer_titres();    // Récupère tous les titres de la BDD
        $messages = _recuperer_messages();  // Récupère tous les messages de la BDD

        _modifier_tailles_varchar();  // Ajuste le VARCHAR à la taille actuelle du vocabulaire
	    _resynchroniser_vecteurs();   // Calcule les vecteurs manquants

    }

    // Ajoute un mot au vocabulaire s'il n'y est pas déjà et s'il n'est pas dans la stop list
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

    // Calcule le vecteur binaire pour le texte passé en paramètre pour pouvoir le comparer avec ceux de la BDD
    function _calculer_vecteur($texte) {

        $texte_nettoye = preg_replace('/[^a-zA-ZÀ-ÿ\s]/', '', strtolower($texte));  // Enlève la ponctuation (ex: "Bonjour," => "bonjour")
        $texte_array = explode(" ", strtolower($texte_nettoye));    // Tableau contenant chaque mot du texte à analyser en minuscule pour comparer avec le vocabulaire
        $nb_mots = count($texte_array);

        // Ouvre le vocabulaire en mode lecture / écriture pour pouvoir ajouter des mots au vocabulaire si besoin et éviter de le fermer et le réouvrir pour actualiser 
        $vocabulaire = fopen(__DIR__ . "/../../vocabulary.txt", "r+");
        if(!$vocabulaire) exit;

        // Ouverture de la stop list en mode lecture seulement (on y ajoute rien dedans)
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