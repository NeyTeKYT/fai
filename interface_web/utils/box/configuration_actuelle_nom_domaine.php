<?php 

    // Détermine le nom de domaine actuellement configuré de la box Internet
	$current_first_name = trim(shell_exec("cat /etc/bind/named.conf.local | grep 'zone' | grep 'ceri.com' | cut -d ' ' -f 2 | cut -d '.' -f 1 | cut -d '\"' -f 2"));
	$dns_domain = $current_first_name . ".ceri.com";

?>