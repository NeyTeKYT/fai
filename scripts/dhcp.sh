#!/bin/bash

# Vérifie que le script a bien été appelé avec un seul argument.
# L'argument est le nombre de machines à inclure dans la plage d'adresses.
if [ "$#" -ne 1 ]
then
	exit 1
fi

# Récupère l'adresse IP de la box Internet
current_ip_address=$(cat /etc/network/interfaces | grep "address" | cut -d" " -f 2)

network_part=$(echo $current_ip_address | cut -d"." -f1-3)

# Définit l'adresse réseau en fonction du masque par défaut /24
network_address=$(echo $network_part | sed 's/$/.0/')

# Récupère le dernier octet
ip_address_last_byte_value=$(echo $current_ip_address | cut -d"." -f 4)

# Définit la valeur du dernier octet
address_range_last_byte_value=$(expr $ip_address_last_byte_value + $1)

# Vérifie que le dernier octet est inférieur
if [ $address_range_last_byte_value -ge 254 ]
then
	exit 2
fi

# Réecriture du fichier
{
  	echo "ddns-update-style none;"
	  echo "option domain-name \"example.org\";"
  	echo "option domain-name-servers ns1.example.org, ns2.example.org;"
  	echo "default-lease-time 600;"
  	echo "max-lease-time 7200;"
  	echo "log-facility local7;"
  	echo ""
	  echo "# Nombre de machines configurées : $1"
  	echo "subnet $network_address netmask 255.255.255.0 {"
  	echo "	range $network_part.$ip_address_last_byte_value $network_part.$address_range_last_byte_value;"
  	echo "}"
  	echo ""
} | sudo tee /etc/dhcp/dhcpd.conf > /dev/null

# Modifier le commentaire du nombre de machines configurées pour éviter d'avoir un décalage vers la droite de l'argument à chaque exécution du script
sudo sed -i "s/^# Nombre de machines configurées :.*/# Nombre de machines configurées : $1/" /etc/dhcp/dhcpd.conf

# Redémarre le serveur DHCP
sudo systemctl restart isc-dhcp-server
