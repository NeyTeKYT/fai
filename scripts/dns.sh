#!/bin/bash

# Vérifie que le script a bien été appelé avec un seul argument :
# - Le prénom : préfixe du domaine "ceri.com" (ex: florent.ceri.com)
if [ "$#" -ne 1 ]
then
	exit 1
fi

# Stockage du prénom choisi par l'utilisateur dans une variable
newFirstName="$1"

# Si le prénom choisi est nul, on met le hostname comme prénom choisi
if [ -z "$newFirstName" ]
then
  newFirstName=$(hostname)
fi

# Récupère le prénom de la configuration actuelle
activeFirstName=$(cat /etc/bind/named.conf.local | grep "zone" | grep "ceri.com" | cut -d " " -f 2 | cut -d "." -f 1 | cut -d '"' -f 2)

# Récupère l'adresse IP de la configuration actuelle.
current_ip_address=$(cat /etc/network/interfaces | grep "address" | cut -d" " -f 2)
# Sépare l'adresse IP en 4 octets
IFS=. read -r ip_octet1 ip_octet2 ip_octet3 ip_octet4 <<< "$current_ip_address"

# Récupère le masque de sous-réseau de la configuration actuelle.
current_subnet_mask=$(cat /etc/network/interfaces | grep "netmask" | cut -d" " -f 2)
# Sépare le masque de sous-réseau en 4 octets
IFS=. read -r subnet_mask_octet1 subnet_mask_octet2 subnet_mask_octet3 subnet_mask_octet4 <<< "$current_subnet_mask"

# Calcule l'adresse réseau
network_address=$(printf "%d.%d.%d.%d\n" "$((ip_octet1 & subnet_mask_octet1))" "$((ip_octet2 & subnet_mask_octet2))" "$((ip_octet3 & subnet_mask_octet3))" "$((ip_octet4 & subnet_mask_octet4))")
IFS=. read -r network_address_octet1 network_address_octet2 network_address_octet3 network_address_octet4 <<< "$network_address"

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

# Détermine quel(s) octet(s) définie/définissent le réseau
if [ "$cidr" -le 8 ]
then 
  network_part=$(echo "$network_address_octet"1)
  ip_address_part=$(echo "$ip_octet2.$ip_octet3.$ip_octet4")
  reversed_network_part=$(echo "$network_address_octet1")
elif [ "$cidr" -le 16 ]
then 
  network_part=$(echo "$network_address_octet1.$network_address_octet2")
  ip_address_part=$(echo "$ip_octet3.$ip_octet4")
  reversed_network_part=$(echo "$network_address_octet2.$network_address_octet1")
elif [ "$cidr" -le 24 ]
then
  network_part=$(echo "$network_address_octet1.$network_address_octet2.$network_address_octet3")
  ip_address_part=$(echo "$ip_octet4")
  reversed_network_part=$(echo "$network_address_octet3.$network_address_octet2.$network_address_octet1")
else
  network_part=$(echo "$network_address_octet1.$network_address_octet2.$network_address_octet3.$network_address_octet4")
  reversed_network_part=$(echo "$network_address_octet4.$network_address_octet3.$network_address_octet2.$network_address_octet1")
fi

# Réecriture du fichier named.conf.local 
{
    # Définition de la zone de recherche directe pour déclarer la zone [prénom].ceri.com
  	echo "zone \"$newFirstName.ceri.com\" {"
  	echo "  type master;"
    echo "  file \"/etc/bind/db.$newFirstName.ceri.com\";"
  	echo "};"
  	echo ""
    # Définition de la zone de recherche inverse
    echo "zone \"$reversed_network_part.in-addr.arpa\" {"
    echo "  type master;"
    echo "  file \"/etc/bind/reverse.$network_part.db\";"
    echo "};"
} | sudo tee /etc/bind/named.conf.local > /dev/null

# Vérifie la syntaxe du fichier named.conf.local 
if ! named-checkconf /etc/bind/named.conf.local
then
  exit 3
fi

# Récupération du SERIAL qui permet de savoir combien de fois a été modifié le fichier forward
nb_serial_db_file=$(cat db.$activeFirstName.ceri.com | grep "Serial" | cut -d ";" -f 1 | tr -d '[:blank:]')
# L'incrémente
(( nb_serial_db_file++ )) 

# Récupération du SERIAL qui permet de savoir combien de fois a été modifié le fichier reverse
nb_serial_reverse_file=$(cat reverse.$network_part.db | grep "Serial" | cut -d ";" -f 1 | tr -d '[:blank:]')
# L'incrémente
(( nb_serial_reverse_file++ )) 

# Supprime le fichier db.[ancienPrénom].ceri.com 
if [ -f "/etc/bind/db.${activeFirstName}.ceri.com" ]
then
  sudo rm "/etc/bind/db.${activeFirstName}.ceri.com"
fi

# Copie du fichier db.local en db.[nouveauPrénom].ceri.com
sudo cp db.local db.$newFirstName.ceri.com

# Modifie le contenu du fichier db.[nouveauPrénom].ceri.com
{
    echo "\$TTL    604800"
    echo "@         IN      SOA     $newFirstName.ceri.com. admin.$newFirstName.ceri.com. ("
    echo "                              $nb_serial_db_file         ; Serial"
    echo "                         604800         ; Refresh"
    echo "                          86400         ; Retry"
    echo "                        2419200         ; Expire"
    echo "                         604800 )       ; Negative Cache TTL"
    echo ""
    echo "@                       IN      NS      $newFirstName.ceri.com."
    echo "${newFirstName}.ceri.com.        IN      A       $current_ip_address"
} | sudo tee /etc/bind/db.$newFirstName.ceri.com > /dev/null

# Supprime les fichiers reverse
sudo rm /etc/bind/reverse*

# Copie du fichier db.127 en reverse.[partieRéseau].db
sudo cp db.127 reverse.$network_part.db

# Modifie le contenu du fichier reverse.[partieRéseau].db
{
    echo "\$TTL    604800"
    echo "@       IN      SOA     $newFirstName.ceri.com. admin.$newFirstName.ceri.com. ("
    echo "                              $nb_serial_reverse_file         ; Serial"
    echo "                         604800         ; Refresh"
    echo "                          86400         ; Retry"
    echo "                        2419200         ; Expire"
    echo "                         604800 )       ; Negative Cache TTL"
    echo ""
    echo "@                     IN      NS      $newFirstName.ceri.com."
    echo "$ip_address_part      IN      PTR     $newFirstName.ceri.com."
} | sudo tee /etc/bind/reverse.$network_part.db > /dev/null

# Crée le dossier où seront stockés les backups du projet s'il n'existe pas déjà
if [ ! -d "/var/backups/FAI" ]
then
	sudo mkdir /var/backups/FAI
fi

# Crée le dossier "dns" dans le dossier /var/backups/FAI s'il n'existe pas déjà pour y stocker les backups des fichiers DNS configurés
if [ ! -d "/var/backups/FAI/dns" ]
then
	sudo mkdir /var/backups/FAI/dns
fi

# Récupère la date pour horodater les backups
date=$(date '+%Y-%m-%d_%H:%M:%S')
sudo mkdir /var/backups/FAI/dns/$date

# Copie du fichier named.conf.local
sudo cp /etc/bind/named.conf.local /var/backups/FAI/dns/$date/named.conf.local
# Copie du fichier db.[Prénom].ceri.com
sudo cp /etc/bind/db.$newFirstName.ceri.com /var/backups/FAI/dns/$date/db.$newFirstName.ceri.com
# Copie du fichier reverse.[partieRéseau].db
sudo cp /etc/bind/reverse.$network_part.db /var/backups/FAI/dns/$date/reverse.$network_part.db

# Redémarre le serveur DNS pour appliquer les changements
sudo systemctl restart bind9

# Création du fichier update_on_isp.txt s'il n'existe pas déjà
if [ ! -f "/etc/bind/update_on_isp.txt" ]
then
  sudo touch /etc/bind/update_on_isp.txt
fi

# Mise en place du fichier de commandes nsupdate pour actualiser la configuration de la zone sur le FAI
{
    echo "server 192.168.1.22"  # IP du serveur DNS du FAI (ISP)
    echo "zone ceri.com"
    echo ""
    # Suppression des deux anciens records
    echo "update delete $activeFirstName.ceri.com. NS"
    echo "update delete $activeFirstName.ceri.com. A"
    # Ajout des nouvelles lignes de la nouvelle configuration
    echo "update add $newFirstName.ceri.com. 86400 NS $newFirstName.ceri.com."
    echo "update add $newFirstName.ceri.com. 86400 A $current_ip_address"
    echo ""
    echo "send"
} | sudo tee /etc/bind/update_on_isp.txt > /dev/null

# Envoie le fichier de commandes au FAI en utilisant la clé TSIG-KEYGEN générée
sudo nsupdate -k /etc/bind/remote-key.key /etc/bind/update_on_isp.txt