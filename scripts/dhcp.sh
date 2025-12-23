#!/bin/bash

# Stockage du mode de configuration comme premier paramètre à l'appel du script
mode=$1

# Si le mode de configuration est le mode débutant, alors le script doit avoir été appelé avec 3 arguments
# - le mode
# - le nombre d'appareils à configurer
# - l'adresse réseau
if [ "$mode" = "debutant" ] && [ "$#" -ne 3 ]
then 
	exit 1
fi

# Si le mode de configuration est le mode avancé, alors le script doit avoir été appelé avec 4 arguments
# - le mode
# - l'adresse IP du début de la plage d'adresses
# - l'adresse IP de fin de la plage d'adresses
# - l'adresse réseau
if [ "$mode" = "avance" ] && [ "$#" -ne 4 ]
then 
	exit 1
fi

# Stockage de l'adresse réseau qui est soit le troisième argument pour le mode débutant ou le quatrième argument pour le mode avancé
network_address=${@: -1}

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
# car on doit compter 2 hôtes pour l'adresse réseau et l'adresse de diffusion ce qui ne laisse plus d'adresse
# libre pour les hôtes 
if [ "$cidr" -ge 31 ]
then
    exit 2
fi

# Calcule le nombre maximum d'hôtes possibles
nb_max_hosts=$(echo "2^(32 - $cidr) - 2" | bc)

# Calcule l'adresse de diffusion = dernière adresse possible dans le sous-réseau
broadcast_octet1=$((network_address_octet1 | (255 - subnet_mask_octet1)))
broadcast_octet2=$((network_address_octet2 | (255 - subnet_mask_octet2)))
broadcast_octet3=$((network_address_octet3 | (255 - subnet_mask_octet3)))
broadcast_octet4=$((network_address_octet4 | (255 - subnet_mask_octet4)))

# Cas où le mode de configuration de l'utilisateur est le mode débutant
if [ "$mode" = "debutant" ] 
then

	# Stockage du nombre d'appareils à configurer pour la plage d'adresses
	devices_number=$2

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

	# Mise en place d'un commentaire pour connaître dans le fichier la configuration de l'utilisateur
	comment="# Nombre de machines configurées : $devices_number"

# Cas où le mode de configuration de l'utilisateur est le mode avancé
else 

	# Stockage des adresses IP de la plage d'adresses dans des variables
	start_ip_in_range=$2
	IFS=. read -r start_octet1 start_octet2 start_octet3 start_octet4 <<< "$start_ip_in_range"
	end_ip_in_range=$3
	IFS=. read -r end_octet1 end_octet2 end_octet3 end_octet4 <<< "$end_ip_in_range"

	# Vérifie que les adresses IP sont bien au format IPv4
	for ip in "$start_ip_in_range" "$end_ip_in_range"
	do
		if ! [[ $ip =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]
		then
			exit 5
		fi
	done

	# Vérifie les valeurs des octets des deux adresses IP pour s'assurer qu'elles sont possibles
	for octet in \
		$start_octet1 $start_octet2 $start_octet3 $start_octet4 \
		$end_octet1   $end_octet2   $end_octet3   $end_octet4
	do
		if [ "$octet" -lt 0 ] || [ "$octet" -gt 255 ]
		then
			exit 6
		fi
	done

	# Vérifie que les IP sont dans le réseau
	for ip_type in start end
	do
		if [ "$ip_type" = "start" ]; then
			o1=$start_octet1; o2=$start_octet2; o3=$start_octet3; o4=$start_octet4
		else
			o1=$end_octet1; o2=$end_octet2; o3=$end_octet3; o4=$end_octet4
		fi

		if [ $((o1 & subnet_mask_octet1)) -ne "$network_address_octet1" ] || \
		[ $((o2 & subnet_mask_octet2)) -ne "$network_address_octet2" ] || \
		[ $((o3 & subnet_mask_octet3)) -ne "$network_address_octet3" ] || \
		[ $((o4 & subnet_mask_octet4)) -ne "$network_address_octet4" ]
		then
			exit 7
		fi
	done

	# Refuse l'adresse réseau et l'adresse de broadcast
	if [ "$start_octet1.$start_octet2.$start_octet3.$start_octet4" = "$network_address" ] || \
	[ "$end_octet1.$end_octet2.$end_octet3.$end_octet4" = \
		"$broadcast_octet1.$broadcast_octet2.$broadcast_octet3.$broadcast_octet4" ]
	then
		exit 8
	fi

	# Vérifie que l'adresse de début est inférieure à l'adresse de fin
	if [ "$start_octet1" -gt "$end_octet1" ] || \
	{ [ "$start_octet1" -eq "$end_octet1" ] && [ "$start_octet2" -gt "$end_octet2" ]; } || \
	{ [ "$start_octet1" -eq "$end_octet1" ] && [ "$start_octet2" -eq "$end_octet2" ] && [ "$start_octet3" -gt "$end_octet3" ]; } || \
	{ [ "$start_octet1" -eq "$end_octet1" ] && [ "$start_octet2" -eq "$end_octet2" ] && [ "$start_octet3" -eq "$end_octet3" ] && [ "$start_octet4" -ge "$end_octet4" ]; }
	then
		exit 9
	fi

	range_size=0

	for o1 in $(seq "$start_octet1" "$end_octet1")
	do
		for o2 in $(seq "$start_octet2" "$end_octet2")
		do
			for o3 in $(seq "$start_octet3" "$end_octet3")
			do
				for o4 in $(seq "$start_octet4" "$end_octet4")
				do
					range_size=$((range_size + 1))
				done
			done
		done
	done

	if [ "$range_size" -gt "$nb_max_hosts" ]
	then
		exit 10
	fi



	# Mise en place d'un commentaire pour connaître dans le fichier la configuration de l'utilisateur
	comment="# Mode avancé : configurer sa propre plage d'adresses"

fi

# Réecriture du fichier de configuration DHCP
{
  	echo "ddns-update-style none;"
	echo "option domain-name \"example.org\";"
  	echo "default-lease-time 30;"	
  	echo "max-lease-time 30;"
  	echo "log-facility local7;"
  	echo ""
	echo "$comment"
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