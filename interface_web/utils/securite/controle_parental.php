<?php 

    // Traitement de la création d'un profil
    if(isset($_POST['add_profil'])) {

        // Stockage des valeurs passées via le formulaire
        $nom = trim($_POST['nom']);
        $age = intval($_POST['age']);
        $adresse_ipv4 = trim($_POST['ip']);
        $id_box = $_SESSION['id'];
        $ia = isset($_POST['ia_planning']);

        creer_profil($nom, $age, $adresse_ipv4, $id_box, $ia);
        header("Location: controle_parental.php");
        exit;

    }

    // Traitement de la modification d'un profil
    if(isset($_POST['edit_profil'])) {

        // Stockage des valeurs passées via le formulaire
        $id_profil = intval($_POST['id_profil']);
        $nom = trim($_POST['nom']);
        $age = intval($_POST['age']);
        $adresse_ipv4 = trim($_POST['ip']);

        modifier_profil($id_profil, $nom, $age, $adresse_ipv4);
        header("Location: controle_parental.php");
        exit;

    }

    // Traitement de la suppression d'un profil
    if(isset($_POST['delete_profil'])) {

        $id_profil = intval($_POST['id_profil']);   // Stockage de l'ID du profil à supprimer

        supprimer_profil($id_profil);
        header("Location: controle_parental.php");
        exit;

    }

    // Traitement de la sauvegarde de la grille
    if(isset($_POST['save_grille'])) {

        // Stockage des valeurs
        $id_profil   = intval($_POST['id_profil']);
        $grille_json = $_POST['grille_json'];

        $result = sauvegarder_grille($id_profil, $grille_json);
        $profil = recuperer_profil($id_profil);

        $bash_ok = false;
        if($result && $profil) $bash_ok = appliquer_controle_parental($profil['adresse_ipv4'], $grille_json);

        echo json_encode(['success' => $result, 'bash_ok' => $bash_ok]);
        exit;

    }

    $profils = recuperer_profils($_SESSION['id']);  // Récupération de tous les profils de la box

    // Profil sélectionné par défaut à NULL jusqu'à ce que l'utilisateur clique sur le bouton "Choisir ce profil"
    $profil_selectionne = null;
    $grille = null;

    // Sélectionne le bon profil après avoir cliqué sur le bouton "Choisir ce profil"
    if(isset($_POST['choose_profil']) && !empty($_POST['profil'])) {
        $id_profil_choisi = intval($_POST['profil']);
        $profil_selectionne = recuperer_profil($id_profil_choisi);
        $grille = recuperer_grille($id_profil_choisi);
    }

?>