<?php

    // Fonction qui calcule la similarité entre deux vecteurs et la retourne
    function cosine_similarity($a, $b) {

        // Vérifie que la longueur est la même pour les deux vecteurs
        $length_a = count($a);
        $length_b = count($b);
        if($length_a !== $length_b) exit("Une erreur est survenue lors du calcul de la similarité entre deux vecteurs !");

        $dot_product = 0.0;
        $magnitude_a = 0.0;
        $magnitude_b = 0.0;

        for($i = 0; $i < $length_a; $i++) {

            $ai = (float) $a[$i];
            $bi = (float) $b[$i];

            $dot_product += $ai * $bi;
            $magnitude_a += pow($ai, 2);
            $magnitude_b += pow($bi, 2);
        }

        // Calculs des racines carrées
        $magnitude_a = sqrt($magnitude_a);
        $magnitude_b = sqrt($magnitude_b);

        if($magnitude_a == 0 || $magnitude_b == 0) return 0.0;  // Empêche la division par 0

        return $dot_product / ($magnitude_a * $magnitude_b);

    }

?>