<?php

    // N'autorise pas l'utilisateur à accéder à cette page si il n'est pas connecté
    session_start();
    if (!isset($_SESSION['id'])) {
        http_response_code(403);
        exit;
    }

    // Fichier JSON
    header('Content-Type: application/json');

    // Récupère les bails actuellement en cours par le serveur DHCP
    // --parsable offre un format plus facile pour récupérer les informations intéressantes
    $raw = shell_exec('sudo dhcp-lease-list --parsable 2>/dev/null');

    $hosts = [];    // Par défaut le nombre d'hôte est 0 car il n'y a pas d'hôte connecté

    // Parcourir toutes les informations espacées par un espace
    foreach(explode("\n", trim($raw)) as $line) {

        if(!$line) continue;

        // Regex pour récupérer les informations sur l'adresse MAC, l'adresse IP et le hostname d'un hôte connecté
        preg_match('/MAC\s+([0-9a-f:]+)/i', $line, $mac);
        preg_match('/IP\s+([0-9.]+)/', $line, $ip);
        preg_match('/HOSTNAME\s+([^ ]+)/', $line, $hostname);

        // Vérifie la bonne récupération des valeurs de l'hôte connecté
        if(empty($mac[1]) || empty($ip[1])) continue;

        // Ajout de l'hôte connecté dans le tableau
        $hosts[] = [
            'mac' => strtolower($mac[1]),
            'ip' => $ip[1],
            'hostname' => $hostname[1] ?? 'Hôte inconnu'
        ];

    }

    // Retourne un JSON contenant le nombre d'hôtes connectés et un tableau avec toutes les informations par hôte
    echo json_encode([
        'count' => count($hosts),
        'hosts' => $hosts
    ]);

?>