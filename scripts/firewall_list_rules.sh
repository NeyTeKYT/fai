#!/bin/bash

# Affiche les règles NAT ajoutées par l’utilisateur
iptables -t nat -L PREROUTING --line-numbers -n | grep USER_RULE

exit 0
