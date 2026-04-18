#!/bin/bash

# Vérifie le bon nombre d'arguments
if [ "$#" -ne 2 ]
then
    exit 1
fi

# Stockage des arguments dans des variables pour pouvoir mieux les manipuler
adresse_ipv4=$1
grille_json=$2

# Vérifie que l'adresse IPv4 est bien au format attendu
if ! [[ "$adresse_ipv4" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]
then
    exit 2
fi

# Vérifie que chaque octet est compris entre 0 et 255
IFS=. read -r octet1 octet2 octet3 octet4 <<< "$adresse_ipv4"
for octet in $octet1 $octet2 $octet3 $octet4
do
    if [ "$octet" -lt 0 ] || [ "$octet" -gt 255 ]
    then
        exit 3
    fi
done

# Vérifie que la grille JSON est valide et contient bien 7 jours x 24 heures
nb_jours=$(echo "$grille_json" | jq 'length')
if [ "$nb_jours" -ne 7 ]
then
    exit 5
fi

for jour in $(seq 0 6)
do
    nb_heures=$(echo "$grille_json" | jq ".[$jour] | length")
    if [ "$nb_heures" -ne 24 ]
    then
        exit 6
    fi
done

# Récupère l'interface réseau de sortie pour les règles FORWARD
interface_sortie=$(ip route | grep default | awk '{print $5}' | head -n 1)
if [ -z "$interface_sortie" ]
then
    exit 7
fi

# Supprime d'abord toutes les règles d'un profil avant de toutes les rajouter pour repartir sur de bonnes bases
sudo iptables -S FORWARD | grep "\-s $adresse_ipv4" | while read -r regle
do
    # Remplace -A par -D pour supprimer la règle existante
    regle_suppression=$(echo "$regle" | sed 's/^-A/-D/')
    sudo iptables $regle_suppression
done

# Abréviations pour les jours en fonction du format des jours d'iptables
jours_iptables=("Mon" "Tue" "Wed" "Thu" "Fri" "Sat" "Sun")

# Pour chaque jour de la semaine
for jour in $(seq 0 6)
do
    jour_iptables="${jours_iptables[$jour]}"

    # Pour chaque heure de la journée
    for heure in $(seq 0 23)
    do
        # Récupère la valeur de la case (0 = autorisé l'accès à Internet, 1 = bloqué)
        bloque=$(echo "$grille_json" | jq ".[$jour][$heure]")

        if [ "$bloque" -eq 1 ]
        then
            # Calcule l'heure de début et de fin de la plage (ex: 18:00 -> 18:59)
            heure_debut=$(printf "%02d:00" "$heure")
            heure_fin=$(printf "%02d:59" "$heure")

            # Applique la règle NAT de blocage pour cette heure
            # -s : adresse IP source (l'appareil du profil)
            # -o : interface de sortie vers Internet
            # -m time : module de gestion du temps
            # --timestart / --timestop : plage horaire de blocage
            # --weekdays : jour de la semaine concerné
            # -j DROP : bloque le paquet
            sudo iptables -A FORWARD \
                -s "$adresse_ipv4" \
                -o "$interface_sortie" \
                -m time \
                --timestart "$heure_debut" \
                --timestop "$heure_fin" \
                --weekdays "$jour_iptables" \
                -j DROP
        fi

    done

done

# Crée le dossier de backup s'il n'existe pas
if [ ! -d "/var/backups/FAI" ]
then
    sudo mkdir /var/backups/FAI
fi

# Crée le dossier iptables s'il n'existe pas
if [ ! -d "/var/backups/FAI/iptables" ]
then
    sudo mkdir /var/backups/FAI/iptables
fi

# Sauvegarde horodatée des règles iptables
date_backup=$(date '+%Y-%m-%d_%H:%M:%S')
sudo iptables-save | sudo tee /var/backups/FAI/iptables/iptables_$date_backup > /dev/null

# Sauvegarde active pour persistance au redémarrage
sudo iptables-save | sudo tee /etc/iptables/rules.v4 > /dev/null

exit 0