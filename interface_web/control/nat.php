<?php

	// Vérifie si l'utilisateur est connecté ou pas
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: ./login.php");
		exit;
	}

	$racine_path = "../";	// Chemin vers la racine

	include($racine_path . "templates/head.php");	// La balise <head> avec toutes les métadonnées 

	include($racine_path . "templates/navbar.php");	// Barre de navigation pour pouvoir se déplacer entre les pages

    // Regarde dans le fichier du routage si il y a un 1 (= pare-feu activé) ou un 0 (= pare-feu désactivé)
    $security_enabled = false;
    if(file_exists('/proc/sys/net/ipv4/ip_forward')) $security_enabled = trim(file_get_contents('/proc/sys/net/ipv4/ip_forward')) === '1';

    // Action au clique sur l'un des boutons 
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Si c'est le bouton qui active la sécurité de la box sur lequel on a cliqué
        if(isset($_POST['enable_security'])) {

            // Si le pare-feu est déjà activé
            if($security_enabled) $message = "<div class='alert alert-danger text-center'>Le pare-feu est déjà activé.</div>";
            else {
                shell_exec("sudo /home/stud/scripts/firewall_enable.sh");
                $message = "<div class='alert alert-success text-center'>La sécurité de la box est activée.</div>";
            }
        }

        // Si c'est le bouton qui désactive la sécurité de la box sur lequel on a cliqué
        else if(isset($_POST['disable_security'])) {

            // Si le pare-feu est déjà désactivé
            if(!$security_enabled) $message = "<div class='alert alert-danger text-center'>Le pare-feu est déjà désactivé.</div>";
            else {
                shell_exec("sudo /home/stud/scripts/firewall_disable.sh");
                $message = "<div class='alert alert-warning text-center'>La sécurité de la box est désactivée.</div>";
            }
        }

        // Si le mode de configuration est le mode avancé et que l'utilisateur souhaite publier une règle au pare-feu
        else if($_SESSION['mode'] === 'avance' && isset($_POST['add_rule'])) {

            // Si le pare-feu est désactivé
            if(!$security_enabled) $message = "<div class='alert alert-danger text-center'>Activez le pare-feu avant d'ajouter une règle.</div>";

            else {

                // Stockage des valeurs envoyées via la requête
                $ip = $_POST['internal_ip'];
                $port_ext = intval($_POST['port_ext']);
                $port_int = intval($_POST['port_int']);
                $proto = ($_POST['proto'] === 'udp') ? 'udp' : 'tcp';

                // Vérifie la cohérence de l'adresse IP
                if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) $message = "<div class='alert alert-danger text-center'>Votre adresse IP fournie pour l'ajout de la règle au pare-feu n'est pas une adresse IPv4 privée !</div>";
                // Vérifie la cohérence des ports extérieurs et intérieurs
                if($port_ext < 1 || $port_ext > 65535) $message = "<div class='alert alert-danger text-center'>Le port extérieur doit être en 1 et 65535 !</div>";
                if($port_int < 1 || $port_int > 65535) $message = "<div class='alert alert-danger text-center'>Le port intérieur doit être en 1 et 65535 !</div>";
                
                else {

                    $script_command = sprintf("sudo /home/stud/scripts/firewall_add_rule.sh %s %d %d %s 2>&1", escapeshellarg($ip), $port_ext, $port_int, escapeshellarg($proto));

                    $output = shell_exec($script_command);

                    if($output === null) $message = "<div class='alert alert-danger'>Erreur d'exécution du script</div>";
                    else $message = "<div class='alert alert-success text-center'>Règle de port-forwarding ajoutée avec succès !<br><strong>$proto</strong> sur le port <strong>$port_ext</strong> est maintenant redirigé vers <strong>$ip:$port_int</strong></div>";

                }
            }
        }

        // Cas où l'utilisateur utilise le mode de configuration avancé et souhaite supprimer une règle
        else if($_SESSION['mode'] === 'avance' && isset($_POST['delete_rule'])) {

            $rule_num = intval($_POST['rule_num']); // Stockage du numéro de la règle à supprimer (pour cibler la bonne règle)

            shell_exec("sudo /home/stud/scripts/firewall_delete_rule.sh $rule_num");

            $message = "<div class='alert alert-success text-center'>La règle a été supprimée avec succès</div>";
        }


    }

    // Spans d’affichage
    $security_span = $security_enabled ? "<span class='text-success fw-bolder'>activée</span>" : "<span class='text-danger fw-bolder'>désactivée</span>";
    $internet_span = $security_enabled ? "<span class='text-success fw-bolder'>actif</span>" : "<span class='text-danger fw-bolder'>inactif</span>";

    // Récupération de toutes les règles crées par l'utilisateur
    $rules = [];

    if($_SESSION['mode'] === 'avance') {
        $output = shell_exec("sudo /home/stud/scripts/firewall_list_rules.sh");
        if(!empty($output)) $rules = explode("\n", trim($output));
    }

	include($racine_path . "templates/nat.php");    // Contient le contenu spécifique de la page de configuration du NAT

	include($racine_path . "templates/footer.php"); // Footer avec les informations du créateur
	
?>
