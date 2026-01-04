#!/bin/bash

# Vérifie le nombre d'arguments
if [ "$#" -ne 1 ]; then
    exit 1
fi

newFirstName="$1"

if [ -z "$newFirstName" ]; then
    newFirstName=$(hostname)
fi

# Prénom actif
activeFirstName=$(grep 'zone' /etc/bind/named.conf.local | grep 'ceri.com' | awk '{print $2}' | cut -d'.' -f1 | tr -d '"')

# IP fiable (CORRECTION MAJEURE)
current_ip_address=$(ip -4 addr show | grep -oP '(?<=inet\s)\d+(\.\d+){3}' | head -n1)

# Masque
current_subnet_mask=$(ip -4 addr show | grep -oP '(?<=inet\s)\d+(\.\d+){3}/\d+' | head -n1 | cut -d/ -f2)

cidr=$current_subnet_mask

IFS=. read -r ip1 ip2 ip3 ip4 <<< "$current_ip_address"

# Réseau /24 (logique conforme à ton infra)
network_part="$ip1.$ip2.$ip3"
reversed_network_part="$ip3.$ip2.$ip1"

# named.conf.local
{
echo "zone \"$newFirstName.ceri.com\" {"
echo "    type master;"
echo "    file \"/etc/bind/db.$newFirstName.ceri.com\";"
echo "};"
echo ""
echo "zone \"$reversed_network_part.in-addr.arpa\" {"
echo "    type master;"
echo "    file \"/etc/bind/reverse.$network_part.db\";"
echo "};"
} | sudo tee /etc/bind/named.conf.local > /dev/null

named-checkconf || exit 3

# SERIAL
serial=$(date +%Y%m%d01)

sudo cp /etc/bind/db.local /etc/bind/db.$newFirstName.ceri.com

{
echo "\$TTL 604800"
echo "@ IN SOA $newFirstName.ceri.com. admin.$newFirstName.ceri.com. ("
echo "    $serial ; Serial"
echo "    604800"
echo "    86400"
echo "    2419200"
echo "    604800 )"
echo ""
echo "@ IN NS $newFirstName.ceri.com."
echo "$newFirstName IN A $current_ip_address"
} | sudo tee /etc/bind/db.$newFirstName.ceri.com > /dev/null

sudo cp /etc/bind/db.127 /etc/bind/reverse.$network_part.db

{
echo "\$TTL 604800"
echo "@ IN SOA $newFirstName.ceri.com. admin.$newFirstName.ceri.com. ("
echo "    $serial ; Serial"
echo "    604800"
echo "    86400"
echo "    2419200"
echo "    604800 )"
echo ""
echo "@ IN NS $newFirstName.ceri.com."
echo "$ip4 IN PTR $newFirstName.ceri.com."
} | sudo tee /etc/bind/reverse.$network_part.db > /dev/null

sudo systemctl restart bind9
