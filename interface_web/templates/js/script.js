//alert("Hello World !")    // Permet de vérifier que le fichier JavaScript est bien interprété par le navigateur

// Exécuté une fois la page totalement chargée
document.addEventListener('DOMContentLoaded', function() {

    // Récupère les valeurs des octets du masque de sous-réseau de la configuration actuelle
    const subnet_mask_octets_values = [
        document.querySelector('select[name="subnet_mask_octet1"]'),
        document.querySelector('select[name="subnet_mask_octet2"]'),
        document.querySelector('select[name="subnet_mask_octet3"]'),
        document.querySelector('select[name="subnet_mask_octet4"]')
    ];

    // Récupère les valeurs des octets de l'adresse IP de la configuration actuelle
    const ip_octets_values = [
        document.getElementById('ip_octet1'),
        document.getElementById('ip_octet2'),
        document.getElementById('ip_octet3'),
        document.getElementById('ip_octet4')
    ];

    // Vérifie que tous les octets de l'adresse IP et du masque de sous-réseau ont bien été récupérés
    // Permet aussi de vérifier si l'on est bien sur la bonne page (formulaire IP) car si nous sommes sur une autre page on ne pourra pas récupérer ces octets
    if(subnet_mask_octets_values.every(e => e) && ip_octets_values.every(e => e)) {

        // Désactive / Active les champs des octets de l'adresse IP en fonction des valeurs du masque de sous-réseau
        function updateIpFields() {
            
            // Convertie les octets du masque de sous-réseau en binaire pour compter le nombre de bits formant la partie réseau
            let binaryMask = subnet_mask_octets_values.map(o => o.value).map(n => ("00000000" + parseInt(n).toString(2)).slice(-8)).join('');
            let networkBits = 0;
            for(let bit of binaryMask) if (bit === '1') networkBits++; else break;

            // Désactive les octets correspondants
            for(let i = 0; i < 4; i++) {
                
                // Désactive un octet de l'adresse IP
                if(networkBits >= 8) {
                    ip_octets_values[i].readOnly = true;
                    networkBits -= 8;
                } 

                // Cas où l'octet n'est pas totalement réservé à la partie réseau
                else if (networkBits > 0) {
                    ip_octets_values[i].readOnly = false;
                    networkBits = 0;    // Inutile de regarder les octets suivants
                } 

                // Définit la partie réseau et la partie hôte pour ne pas que le client prenne une valeur de la partie réseau
                else {
                    ip_octets_values[i].readOnly = false;
                    let maxHostValue = Math.pow(2, 8 - networkBits) - 1; // 2^(3) - 1 = 7
                    ip_octets_values[i].max = maxHostValue;
                    networkBits = 0;
                }
            }
        }

        // Modifie les valeurs du masque de sous-réseau
        subnet_mask_octets_values.forEach(octet => octet.addEventListener('change', updateIpFields));

        updateIpFields();   // Met à jour automatiquement les champs modifiables et les champs fixes

    }

    // Récupérations des éléments HTML sur lesquels le JavaScript va travailler
    const hostsList = document.getElementById('dhcp-hosts-list');
    const noHostsMsg = document.getElementById('no-hosts-msg');
    const usersCount = document.getElementById('nb-users-connected');

    // Mise en place d'une structure de données pour stocker les hôtes actuellement connectés
    let currentHosts = new Map();   // Une map contient des paires clé-valeur et mémorise l'ordre dans lequel les clés ont été insérées

    function renderHosts(hosts) {

        // Mise en place de la future nouvelle structure de données où on va actualiser les hôtes connectés
        const newHosts = new Map();

        hosts.forEach(h => {
            newHosts.set(h.mac, h); // Associe une adresse MAC à un hôte, pas l'adresse IP car elle peut changer d'un hôte à un autre
        });

        // Suppression des hôtes déconnectés
        currentHosts.forEach((_, mac) => {

            if(!newHosts.has(mac)) {
                const el = document.getElementById(`host-${mac.replace(/:/g, '')}`);
                if(el) el.remove();
            }

        });

        // Ajout des nouveaux hôtes
        newHosts.forEach((host, mac) => {

            if(!currentHosts.has(mac)) {

                // Affichage d'un hôte dans une card Bootstrap
                const item = document.createElement('div');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                item.id = `host-${mac.replace(/:/g, '')}`;

                // Informations de l'hôte
                item.innerHTML = `
                    <div>
                        <strong>${host.hostname}</strong><br>
                        <small>
                            Adresse IP : ${host.ip} |
                            Adresse MAC : ${host.mac}
                        </small>
                    </div>
                `;

                // Ajout dans la Map
                hostsList.appendChild(item);

            }

        });

        currentHosts = newHosts;    // Remplace la structure de données par la nouvelle

        // Mise à jour du nombre d'utilisateurs connectés
        usersCount.innerHTML = `<strong>Nombre d'appareils connectés :</strong> ${hosts.length}`;

        // Ajout d'un message quand il n'y a pas d'hôte connecté à la Box Internet pour en informer l'utilisaeur
        if(hosts.length === 0) noHostsMsg.classList.remove('d-none');
        else noHostsMsg.classList.add('d-none');

    }

    // Centralise le processus Ajax pour récupérer les hôtes DHCP actuellement connectés 
    function fetchDhcpHosts() {

        fetch('/interface_web/control/dhcp_hosts.php')
            .then(response => response.json())
            .then(data => {
                const hosts = Array.isArray(data.hosts) ? data.hosts : [];
                renderHosts(hosts);
            })
            .catch(err => console.error('DHCP AJAX error:', err));

    }

    // Une requête toutes les 3 secondes
    setInterval(fetchDhcpHosts, 3000);

    // Récupère les hôtes DHCP
    fetchDhcpHosts();

});

