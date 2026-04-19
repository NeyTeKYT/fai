<?php

    // Récupération du nom de domaine de la box
    $domain = trim(shell_exec("grep 'zone' /etc/bind/named.conf.local | grep 'ceri.com' | awk '{print $2}' | tr -d '\";' | cut -d. -f1"));

    // Détermine le fichier qui définit la zone [prénom].ceri.com
    $zone = "/etc/bind/db.$domain.ceri.com";

    $hosts = [];    // Par défaut le nombre d'hôte est 0 car il n'y a pas d'hôte connecté

    // Si le fichier qui définit la zone [prénom].ceri.com existe
    if(file_exists($zone)) {

        // Parcourir chaque ligne du fichier
        foreach(file($zone) as $line) {

            // REGEX qui récupère seulement les lignes du style (ns1 IN A 192.168.1.1) sans IN et A
            if(preg_match('/^([a-z0-9-]+)\s+IN\s+A\s+([0-9.]+)/i', $line, $host)) {

                $hosts[] = [
                    'host' => $host[1], // Le premier élément est le préfixe / l'alias du domaine ceri.com
                    'ip'   => $host[2]  // Le deuxième élément est l'adresse IP associée au nom de domaine
                ];

            }

        }
    }

    // Retourne un JSON contenant le nombre d'hôtes connectés et un tableau avec toutes les informations par hôte
    echo json_encode([
        'domain' => "$domain.ceri.com",
        'hosts'  => $hosts
    ]);

?>