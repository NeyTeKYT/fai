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

        // Ajout des nouveaux hôtes connectés
        newHosts.forEach((host, mac) => {

            if(!currentHosts.has(mac)) {

                // Affichage d'un hôte dans une card Bootstrap
                const item = document.createElement('div');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                item.id = `host-${mac.replace(/:/g, '')}`;

                // Affichage de l'hôte avec ses informations
                item.innerHTML = `
                    <div>
                        <strong>${host.hostname}</strong><br>
                        <small>
                            Adresse IP : ${host.ip} |
                            Adresse MAC : ${host.mac}
                        </small>
                    </div>
                `;

                // Ajout du nouvel hôte dans la Map
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
            .catch(err => console.error('Erreur Ajax:', err));

    }

    // Une requête toutes les 3 secondes
    setInterval(fetchDhcpHosts, 3000);

    // Récupère les hôtes DHCP
    fetchDhcpHosts();

    // Récupérations des éléments HTML sur lesquels le JavaScript va travailler
    const dnsCard  = document.getElementById('dns-hosts-card');
    const dnsTable = document.getElementById('dns-hosts-table');
    const noDnsMsg = document.getElementById('no-dns-hosts-msg');

    // Cette partie du JavaScript ne concerne que le cas où on est sur le formulaire DNS
    if(!dnsCard || !dnsTable || !noDnsMsg) return;

    function renderDnsHosts(data) {

        dnsTable.innerHTML = '';

        // Cas où aucun sous-domaine n'est configuré
        if(!Array.isArray(data.hosts) || data.hosts.length === 0) {
            dnsCard.classList.add('d-none');
            noDnsMsg.classList.remove('d-none');
            return;
        }

        // Cas où au moins un sous-domaine est configuré

        dnsCard.classList.remove('d-none');
        noDnsMsg.classList.add('d-none');

        data.hosts.forEach(h => {

            // Création d'une ligne au tableau répertoriant la liste des sous-domaines crées
            const tr = document.createElement('tr');

            // Affichage de la ligne du sous-domaine dans le tableau
            tr.innerHTML = `
                <td>${h.host}.${data.domain}</td>
                <td>${h.ip}</td>
                <td class="text-end">
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="delete_host" value="${h.host}">
                        <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Supprimer ${h.host} ?')">
                            Supprimer
                        </button>
                    </form>
                </td>
            `;

            // Ajout de la ligne au tableau
            dnsTable.appendChild(tr);

        });
        
    }

    // Centralise le processus Ajax pour récupérer les sous-domaines crées
    function fetchDnsHosts() {

        fetch('/interface_web/control/dns_hosts.php')
            .then(response => response.json())
            .then(renderDnsHosts)
            .catch(err => console.error('Erreur Ajax', err));

    }

    // Récupère sous-domaines DNS
    fetchDnsHosts();

    // Une requête toutes les 5 secondes
    setInterval(fetchDnsHosts, 5000);

});

