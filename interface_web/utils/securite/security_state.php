<?php 

    // Regarde dans le fichier du routage si il y a un 1 (= pare-feu activé) ou un 0 (= pare-feu désactivé)
    $security_enabled = false;
    if(file_exists('/proc/sys/net/ipv4/ip_forward')) $security_enabled = trim(file_get_contents('/proc/sys/net/ipv4/ip_forward')) === '1';

    // Spans d’affichage
    $security_span = $security_enabled ? "<span class='text-success fw-bolder'>activée</span>" : "<span class='text-danger fw-bolder'>désactivée</span>";
    $internet_span = $security_enabled ? "<span class='text-success fw-bolder'>actif</span>" : "<span class='text-danger fw-bolder'>inactif</span>";

?>