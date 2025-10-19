#!/bin/bash

# Vérifie que le script a bien été appelé avec un seul argument.
# L'argument est la nouvelle adresse IP.
if [ "$#" -ne 1 ]
then
	exit 1
fi

# Récupère l'adresse IP de la configuration actuelle
current_ip_address=$(cat /etc/network/interfaces | grep "address" | cut -d" " -f 2)

# Compare l'adresse IP de la configuration actuelle avec l'argument.
if [ $current_ip_address == $1 ]
then
	exit 2
fi

# Remplace l'adresse IP par la nouvelle
sudo sed -i "s/address $current_ip_address/address $1/g" /etc/network/interfaces

# Crée le dossier backups s'il n'existe pas déjà
cd /var/backups/
if [ ! -d "backups" ]
then
	mkdir /var/backups/FAI
fi

# Génère un backup horodaté du fichier /etc/network/interfaces
date=$(date '+%Y-%m-%d_%H:%M:%S')
cp /etc/network/interfaces /var/backups/FAI/interfaces_$date

# Ferme l'interface
sudo ifdown eth1

# Supprime toutes les adresses IP de l'interface
sudo ip addr flush dev eth1

# Ouvre l'interface
sudo ifup eth1
