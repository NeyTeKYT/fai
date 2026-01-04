#!/bin/bash

# Vérifie que le script a bien été appelé avec 4 arguments
# - L’adresse IP de destination
# - Le port externe
# - Le port interne
# - Le protocole (tcp / udp)
if [ "$#" -ne 4 ]
then
	exit 1
fi

# Stockage des arguments
destination_ip=$1
external_port=$2
internal_port=$3
protocol=$4

# Vérifie que le protocole est valide
if [ "$protocol" != "tcp" ] && [ "$protocol" != "udp" ]
then
	exit 2
fi

# Ajout de la règle DNAT
iptables -t nat -A PREROUTING -i eth0 -p "$protocol" --dport "$external_port" \
	-j DNAT --to-destination "$destination_ip:$internal_port" \
	-m comment --comment "USER_RULE"

# Autorise le forwarding
iptables -A FORWARD -p "$protocol" -d "$destination_ip" --dport "$internal_port" \
	-m conntrack --ctstate NEW,ESTABLISHED,RELATED -j ACCEPT \
	-m comment --comment "USER_RULE"

exit 0
