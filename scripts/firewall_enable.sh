#!/bin/bash

# Active le routage IP
echo 1 > /proc/sys/net/ipv4/ip_forward

# Politiques par défaut
iptables -P INPUT ACCEPT
iptables -P OUTPUT ACCEPT
iptables -P FORWARD DROP

# Autorise la boucle locale
iptables -A INPUT -i lo -j ACCEPT

# Autorise les connexions établies
iptables -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
iptables -A FORWARD -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT

# Autorise le serveur web
iptables -A INPUT -p tcp --dport 80 -j ACCEPT

# Autorise le LAN à accéder à Internet
iptables -A FORWARD -i eth1 -o eth0 -j ACCEPT

# Active le NAT
iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE

exit 0
