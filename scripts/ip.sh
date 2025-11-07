#!/bin/bash

# Vérifie que le script a bien été appelé avec deux arguments.
# - L'adresse IP (argument 1)
# - Le masque de sous-réseau (argument 2)
if [ "$#" -ne 2 ]
then
	exit 1
fi

# Récupère l'adresse IP de la configuration actuelle.
current_ip_address=$(cat /etc/network/interfaces | grep "address" | cut -d" " -f 2)
# Récupère le masque de sous-réseau de la configuration actuelle.
current_subnet_mask=$(cat /etc/network/interfaces | grep "netmask" | cut -d" " -f 2)

# Vérifie qu'un changement a été effectué par le client.
if [ $current_ip_address == $1 ] && [ $current_subnet_mask == $2 ]
then
	exit 2
fi

# Remplace l'adresse IP par la nouvelle
sudo sed -i "s/address $current_ip_address/address $1/g" /etc/network/interfaces
# Remplace le masque de sous-réseau par le nouveau
sudo sed -i "s/netmask $current_subnet_mask/netmask $2/g" /etc/network/interfaces

# Crée le dossier backups du projet s'il n'existe pas déjà.
cd /var/backups/
if [ ! -d "FAI" ]
then
	sudo mkdir /var/backups/FAI
fi

# Crée le dossier ip dans le dossier backups FAI s'il n'existe pas déjà.
cd /var/backups/FAI
if [ ! -d "ip" ]
then
	sudo mkdir /var/backups/FAI/ip
fi

# Génère un backup horodaté du fichier /etc/network/interfaces.
date=$(date '+%Y-%m-%d_%H:%M:%S')
sudo cp /etc/network/interfaces /var/backups/FAI/ip/interfaces_$date

# Ferme l'interface
sudo ifdown eth1

# Supprime toutes les adresses IP de l'interface
sudo ip addr flush dev eth1

# Ouvre l'interface
sudo ifup eth1

