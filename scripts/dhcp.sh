#!/bin/bash

# Vérifie que le script a bien été appelé avec deux arguments :
# - Le nombre de machines à inclure dans la plage d'adresses,
# - L'adresse réseau
if [ "$#" -ne 2 ]
then
	exit 1
fi

# Stockage des arguments 
devices_number=$1
network_address=$2

# Sépare l'adresse réseau en 4 octets
IFS=. read -r network_address_octet1 network_address_octet2 network_address_octet3 network_address_octet4 <<< "$network_address"

# Récupère l'adresse IP 
current_ip_address=$(cat /etc/network/interfaces | grep "address" | cut -d" " -f2)
# Sépare l'adresse IP en 4 octets
IFS=. read -r ip_octet1 ip_octet2 ip_octet3 ip_octet4 <<< "$current_ip_address"

# Récupère le masque de sous-réseau
current_subnet_mask=$(cat /etc/network/interfaces | grep "netmask" | cut -d" " -f2)
# Sépare le masque de sous-réseau en 4 octets
IFS=. read -r subnet_mask_octet1 subnet_mask_octet2 subnet_mask_octet3 subnet_mask_octet4 <<< "$current_subnet_mask"

# Calcule le CIDR (nombre de bits à 1 dans le masque de sous-réseau)
cidr=0
for octet in $subnet_mask_octet1 $subnet_mask_octet2 $subnet_mask_octet3 $subnet_mask_octet4
do
	# Convertie l'octet en binaire
	binary=$(printf "%08d" "$(bc <<< "obase=2;$octet")")	
	# Compte combien de bits sont à 1
	count=$(grep -o "1" <<< "$binary" | wc -l)	
	# Incrémente le CIDR
	cidr=$((cidr + count))	
done

# Refuse les masques de sous-réseaux /31 et /32 (non adaptés au DHCP classique)
if [ "$cidr" -ge 31 ]
then
    exit 2
fi

# Calcule le nombre maximum d'hôtes (2^(32-cidr) - 2)
nb_max_hosts=$(echo "2^(32 - $cidr) - 2" | bc)

# Vérifie si le nombre d'appareils est inférieur au seuil maximum d'hôtes en fonction du masque de sous-réseau
if [ "$devices_number" -gt "$nb_max_hosts" ]
then
	exit 3
fi

# Définit la valeur du dernier octet pour la première adresse IP de la plage
start_octet_value_in_range=$((ip_octet4 + 1))
# Définit la première adresse IP de la plage
start_ip_in_range="$ip_octet1.$ip_octet2.$ip_octet3.$start_octet_value_in_range"

# Définit la valeur du dernier octet pour la dernière adresse IP de la plage
end_octet_value_in_range=$((ip_octet4 + devices_number))

# Vérifie que la valeur du dernier octet de la dernière adresse IP de la plage d'adresses ne dépasse pas 254
if [ "$end_octet_value_in_range" -gt 254 ]
then
	exit 4
fi

# Définit la dernière adresse IP de la plage
end_ip_in_range="$ip_octet1.$ip_octet2.$ip_octet3.$end_octet_value_in_range"

# Réecriture du fichier de configuration DHCP
{
  	echo "ddns-update-style none;"
	echo "option domain-name \"example.org\";"
  	#echo "option domain-name-servers ns1.example.org, ns2.example.org;"
  	echo "default-lease-time 600;"
  	echo "max-lease-time 7200;"
  	echo "log-facility local7;"
  	echo ""
	echo "# Nombre de machines configurées : $devices_number"
  	echo "subnet $network_address netmask $current_subnet_mask {"
  	echo "	range $start_ip_in_range $end_ip_in_range;"
	echo "	option domain-name-servers $current_ip_address;"
  	echo "}"
  	echo ""
} | sudo tee /etc/dhcp/dhcpd.conf > /dev/null

# Met à jour le commentaire sur le nombre de machines configurées
#sudo sed -i "s/^# Nombre de machines configurées :.*/# Nombre de machines configurées : $devices_number/" /etc/dhcp/dhcpd.conf

# Crée le dossier où seront stockés les backups du projet s'il n'existe pas déjà
if [ ! -d "/var/backups/FAI" ]
then
	sudo mkdir /var/backups/FAI
fi

# Crée le dossier "dhcp" dans le dossier /var/backups/FAI s'il n'existe pas déjà pour y stocker les backups du fichier "dhcpd"
if [ ! -d "/var/backups/FAI/dhcp" ]
then
	sudo mkdir /var/backups/FAI/dhcp
fi

# Génère un backup horodaté du fichier /etc/dhcp/dhcpd.conf.
date=$(date '+%Y-%m-%d_%H:%M:%S')
sudo cp /etc/dhcp/dhcpd.conf /var/backups/FAI/dhcp/dhcpd_$date

# Redémarre le serveur DHCP
sudo systemctl restart isc-dhcp-server