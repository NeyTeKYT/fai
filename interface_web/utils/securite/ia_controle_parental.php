<?php

    // Calcule un planning basé sur les profils d'âge similaire
    function ia_planning($age_cible) {

        global $pdo;

        // Récupère tous les profils ayant un planning défini
        $stmt = $pdo->prepare("SELECT profil.age, planning_controle_parental.grille FROM profil INNER JOIN planning_controle_parental ON planning_controle_parental.id_profil = profil.id");
        $stmt->execute();
        $profils = $stmt->fetchAll();

        if(empty($profils)) return null;    // Si il n'y a aucun planning alors impossible d'utiliser l'IA

        // Initialise la grille vide 7x24 avec des poids à 0 pour le moment
        $grille_ponderee = array_fill(0, 7, array_fill(0, 24, 0.0));
        $poids_total = 0.0;

        foreach($profils as $profil) {

            $age_profil = intval($profil['age']);
            $grille = json_decode($profil['grille'], true);

            if(!$grille) continue;

            // Calcule la distance d'âge entre le profil existant et le profil à créer
            $distance_age = abs($age_cible - $age_profil);
            $poids = 1.0 / (1.0 + $distance_age);

            // Accumule les valeurs pondérées heure par heure
            for($j = 0; $j < 7; $j++) {
                for($h = 0; $h < 24; $h++) $grille_ponderee[$j][$h] += $grille[$j][$h] * $poids;
            }

            $poids_total += $poids;

        }

        if($poids_total == 0) return null;

        $grille_suggeree = [];
        for($j = 0; $j < 7; $j++) {
            $grille_suggeree[$j] = [];
            for($h = 0; $h < 24; $h++) {
                $moyenne = $grille_ponderee[$j][$h] / $poids_total;
                if($moyenne >= 0.5) $grille_suggeree[$j][$h] = 1;
                else $grille_suggeree[$j][$h] = 0;
            }
        }

        return $grille_suggeree;

    }

?>