<?php 

    // Fonction qui détermine quels sont les titres / messages qui se rapprochent le plus du texte à traiter
    function verifier_ia($texte, $nom_table) {

        global $pdo; // Permet d'accéder à la variable globale $pdo

        $vecteur_texte = calculer_vecteur($texte);    // Calcule le vecteur binaire pour le texte passé en paramètre pour pouvoir le comparer avec ceux de la BDD

        // Valide le paramètre du nom de la table
        $allowed = ['discussion', 'message'];
        if(!in_array($nom_table, $allowed)) exit("Une erreur est survenue lors de l'exécution de l'IA !");

        // Récupération des discussions OU messages en fonction du champ
        $stmt = $pdo->query("SELECT * FROM $nom_table");
        $data = $stmt->fetchAll();  // Tableau contenant toutes les discussions OU messages en fonction du champ

        $cosine_similarity = [];    // Vecteur contenant les calculs des similarités entre le texte et ceux de la BDD

        // Création d'un vecteur pour l'attribut vecteur de la table
        for($i = 0; $i < count($data); $i++) {
            $vecteur_table = str_split($data[$i]['vecteur']);
            $cosine_similarity[$i] = cosine_similarity($vecteur_texte, $vecteur_table);
        }
        arsort($cosine_similarity); // Trie le vecteur du plus grand au plus petit

        $ind = array_slice(array_keys($cosine_similarity), 0, 3);   // Indices des 3 meilleures résultats

        $similar_discussions = [];

        // Détermine de quel titre / message le résultat du cos correspond
        foreach($ind as $i) {
            $data[$i]['score'] = $cosine_similarity[$i];
            $similar_discussions[] = $data[$i];
        } 

        return $similar_discussions;

    }

?>