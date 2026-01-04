#!/bin/bash

# Active le routage IP
echo 0 > /proc/sys/net/ipv4/ip_forward

# On vide successivement les chaines par defaut
iptables -F INPUT
iptables -F OUTPUT
iptables -F FORWARD

# On vide d'autres chaines, dans d'autres contextes. On verra plus tard l'intérêt.
iptables -t nat -F POSTROUTING
iptables -t nat -F PREROUTING
iptables -t raw -F PREROUTING
iptables -t raw -F OUTPUT

# On vide et on detruit une chaine "utilisateur" LOGDROP
# qui n'existe pas forcement, mais on ne s'en inquiete pas trop.
iptables -F LOGDROP
iptables -X LOGDROP

# On cree la chaine utilisateur LOGDROP qui va successivement
# journaliser les paquets avec un FW_DENIED devant (notez l'espace apres)
# puis jeter les paquets
iptables -N LOGDROP
iptables -A LOGDROP -j LOG --log-prefix "FW_DENIED "
iptables -A LOGDROP -j DROP