#!/bin/bash

# Vérifie que le script a bien été appelé avec deux arguments
# - L'adresse IP 
# - Le masque de sous-réseau 
if [ "$#" -ne 2 ]
then
	exit 1
fi

# Stockage des arguments dans des variables
new_ip_address=$1
new_subnet_mask=$2

# Récupère l'adresse IP de la configuration actuelle
current_ip_address=$(cat /etc/network/interfaces | grep "address" | cut -d" " -f 2)
# Récupère le masque de sous-réseau de la configuration actuelle
current_subnet_mask=$(cat /etc/network/interfaces | grep "netmask" | cut -d" " -f 2)

# Vérifie qu'un changement a été effectué par l'utilisateur
if [ "$current_ip_address" == "$new_ip_address" ] && [ "$current_subnet_mask" == "$new_subnet_mask" ]
then
	exit 2
fi

# Remplace l'adresse IP par la nouvelle
sudo sed -i "s/address $current_ip_address/address $new_ip_address/g" /etc/network/interfaces
# Remplace le masque de sous-réseau par le nouveau
sudo sed -i "s/netmask $current_subnet_mask/netmask $new_subnet_mask/g" /etc/network/interfaces

# Crée le dossier où seront stockés les backups du projet s'il n'existe pas déjà
if [ ! -d "/var/backups/FAI" ]
then
	sudo mkdir /var/backups/FAI
fi

# Crée le dossier "ip" dans le dossier /var/backups/FAI s'il n'existe pas déjà pour y stocker les backups du fichier "interfaces"
if [ ! -d "/var/backups/FAI/ip" ]
then
	sudo mkdir /var/backups/FAI/ip
fi

# Génère un backup horodaté du fichier /etc/network/interfaces.
date=$(date '+%Y-%m-%d_%H:%M:%S')
sudo cp /etc/network/interfaces /var/backups/FAI/ip/interfaces_$date

# Ferme l'interface
#sudo ifdown eth1
# Supprime toutes les adresses IP de l'interface
#sudo ip addr flush dev eth1
# Ouvre l'interface
#sudo ifup eth1

# Recharge l'interface sans couper brutalement la connexion
sudo systemctl restart networking

# Exécute les scripts DHCP et DNS pour modifier l'adresse IP dans leurs fichiers de configurations
cd /home/stud/scripts

# Récupère le nombre actuel de machines configurées avec la plage d'adresses DHCP
devices_number=$(cat /etc/dhcp/dhcpd.conf | grep "Nombre de machines configurées" | cut -d":" -f 2 | tr -d '[:blank:]')

# Calcule l'adresse réseau
# Décompose la nouvelle adresse IP de la box Internet en 4 octets
IFS=. read -r ip_octet1 ip_octet2 ip_octet3 ip_octet4 <<< "$new_ip_address"
# Décompose le nouveau masque de sous-réseau de la box Internet en 4 octets
IFS=. read -r subnet_mask_octet1 subnet_mask_octet2 subnet_mask_octet3 subnet_mask_octet4 <<< "$new_subnet_mask"
# Calcule l'adresse réseau
network_address=$(printf "%d.%d.%d.%d\n" "$((ip_octet1 & subnet_mask_octet1))" "$((ip_octet2 & subnet_mask_octet2))" "$((ip_octet3 & subnet_mask_octet3))" "$((ip_octet4 & subnet_mask_octet4))")

# Exécute le script DHCP pour mettre à jour les informations
sudo ./dhcp.sh $devices_number $network_address

# Récupère le prénom configuré comme préfixe du domaine ceri.com
activeFirstName=$(cat /etc/bind/named.conf.local | grep "zone" | grep "ceri.com" | cut -d " " -f 2 | cut -d "." -f 1 | cut -d '"' -f 2)

# Exécute le script DNS pour mettre à jour les informations
sudo ./dns.sh $activeFirstName

# Retourne 0 pour le $retval du formulaire
exit 0