//alert("Hello World !")    // Permet de vérifier que le fichier JavaScript est bien interprété par le navigateur

// Autoriser les tools tips 
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

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

    updateIpFields();

});