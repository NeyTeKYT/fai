#!/bin/bash

# Vérifie que le script a bien été appelé avec 3 arguments
# - Le nom d’hôte
# - Le nom de domaine
# - L’adresse IP
if [ "$#" -ne 3 ]
then
	exit 1
fi

# Stockage des arguments
host_name=$1
domain_name=$2
ip_address=$3

# Fichier de zone directe
zone_file="/etc/bind/db.$domain_name"

# Sépare l’adresse IP en 4 octets
IFS=. read -r ip_octet1 ip_octet2 ip_octet3 ip_octet4 <<< "$ip_address"

# Fichier de zone inverse
reverse_file="/etc/bind/reverse.$ip_octet1.$ip_octet2.$ip_octet3.db"

# Suppression des anciennes entrées DNS si elles existent
sed -i "/^[[:space:]]*$host_name[[:space:]]\+IN[[:space:]]\+A/d" "$zone_file"
sed -i "/^[[:space:]]*$ip_octet4[[:space:]]\+IN[[:space:]]\+PTR/d" "$reverse_file"

# Ajout de la nouvelle entrée DNS
echo "$host_name IN A $ip_address" >> "$zone_file"
echo "$ip_octet4 IN PTR $host_name.$domain_name." >> "$reverse_file"

# Incrémentation du SERIAL dans la zone directe
sed -i -E '
/Serial/{
	s/^[[:space:]]*([0-9]+)/echo $((\1+1))/e
}
' "$zone_file"

# Redémarrage du service DNS
sudo systemctl restart bind9

exit 0
