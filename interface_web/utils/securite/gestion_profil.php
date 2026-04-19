<?php

    // Crée un profil dans la BDD
    function creer_profil($nom, $age, $adresse_ipv4, $id_box, $utiliser_ia = false) {

        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO profil (id_box, nom, age, adresse_ipv4) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$id_box, $nom, $age, $adresse_ipv4]);

        if($result) {

            $id_nouveau_profil = $pdo->lastInsertId();

            // Si l'IA est activée alors on utilise l'IA pour stocker directement un planning généré
            if($utiliser_ia) {

                $grille_suggeree = ia_planning($age);

                if($grille_suggeree) {
                    sauvegarder_grille($id_nouveau_profil, json_encode($grille_suggeree));
                    $_SESSION['message'] = "<div class='alert alert-success text-center'>Le profil <strong>$nom</strong> (<strong>$age ans</strong>) a bien été créé et <strong>un planning a été généré par l'IA</strong> !</div>";
                } 

                else $_SESSION['message'] = "<div class='alert alert-success text-center'>Le profil <strong>$nom</strong> a été créé mais <strong>aucun planning similaire n'a pu être trouvé</strong>.</div>";

            } 
            
            else $_SESSION['message'] = "<div class='alert alert-success text-center'>Le profil <strong>$nom</strong> (<strong>$age ans</strong>) a bien été créé et lié à l'appareil <strong>$adresse_ipv4</strong> !</div>";

        }

        else $_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la création du profil !</div>";

    }

    // Récupère tous les profils d'une box Internet
    function recuperer_profils($id_box) {

        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM profil WHERE id_box = ? ORDER BY id ASC"); // Dans l'ordre de création des profils
        $stmt->execute([$id_box]);
        return $stmt->fetchAll();

    }

    // Récupère un profil grâce à son ID (clé primaire)
    function recuperer_profil($id_profil) {

        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM profil WHERE id = ?");
        $stmt->execute([$id_profil]);
        return $stmt->fetch();

    }

    // Sauvegarde la grille de contrôle parental pour un profil
    // La grille est un tableau de 7 jours x 24 heures (0 = autorisé, 1 = bloqué)
    function sauvegarder_grille($id_profil, $grille_json) {

        global $pdo;

        // Vérifie si une grille existe déjà pour ce profil
        $stmt = $pdo->prepare("SELECT id FROM planning_controle_parental WHERE id_profil = ?");
        $stmt->execute([$id_profil]);
        $existant = $stmt->fetch();

        if($existant) {
            $stmt = $pdo->prepare("UPDATE planning_controle_parental SET grille = ? WHERE id_profil = ?");
            $result = $stmt->execute([$grille_json, $id_profil]);
        } 
        else {
            $stmt = $pdo->prepare("INSERT INTO planning_controle_parental (id_profil, grille) VALUES (?, ?)");
            $result = $stmt->execute([$id_profil, $grille_json]);
        }

        if($result) $_SESSION['message'] = "<div class='alert alert-success text-center'>Le planning a bien été sauvegardé !</div>";
        else $_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la sauvegarde du planning !</div>";

        return $result;

    }

    // Récupère la grille de contrôle parental pour un profil
    function recuperer_grille($id_profil) {

        global $pdo;

        $stmt = $pdo->prepare("SELECT grille FROM planning_controle_parental WHERE id_profil = ?");
        $stmt->execute([$id_profil]);
        $row = $stmt->fetch();

        if(!$row) return array_fill(0, 7, array_fill(0, 24, 0));    // Si aucune grille n'existe, retourne une grille vide (tout autorisé)

        return json_decode($row['grille'], true);

    }

    // Exécute le script controle_parental.sh pour ajouter les règles iptables
    function appliquer_controle_parental($adresse_ipv4, $grille_json) {

        $script_path = "/home/stud/scripts/controle_parental.sh";

        $ip_safe = escapeshellarg($adresse_ipv4);
        $grille_safe = escapeshellarg($grille_json);

        $commande = "sudo $script_path $ip_safe $grille_safe 2>/dev/null";
        exec($commande, $output, $code_retour);

        return $code_retour === 0;

    }

    // Modifie un profil existant dans la BDD
    function modifier_profil($id_profil, $nom, $age, $adresse_ipv4) {

        global $pdo;

        $stmt = $pdo->prepare("UPDATE profil SET nom = ?, age = ?, adresse_ipv4 = ? WHERE id = ?");
        $result = $stmt->execute([$nom, $age, $adresse_ipv4, $id_profil]);

        if($result) $_SESSION['message'] = "<div class='alert alert-success text-center'>Le profil <strong>$nom</strong> (<strong>$age ans</strong>) a bien été modifié pour l'appareil <strong>$adresse_ipv4</strong> !</div>";
        else $_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la modification du profil !</div>";

    }

    // Supprime un profil de la BDD ainsi que sa grille (si on supprime un profil alors l'enregistrement correspondant dans la table planning_controle_parental sera lui aussi supprimé)
    function supprimer_profil($id_profil) {

        global $pdo;

        // Supprime d'abord la grille liée au profil
        $stmt = $pdo->prepare("DELETE FROM planning_controle_parental WHERE id_profil = ?");
        $stmt->execute([$id_profil]);

        // Supprime ensuite le profil
        $stmt = $pdo->prepare("DELETE FROM profil WHERE id = ?");
        $result = $stmt->execute([$id_profil]);

        if($result) $_SESSION['message'] = "<div class='alert alert-success text-center'>Le profil a bien été supprimé !</div>";
        else $_SESSION['message'] = "<div class='alert alert-danger text-center'>Une erreur est survenue lors de la suppression du profil !</div>";

    }

?>