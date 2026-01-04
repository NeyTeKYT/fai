#!/bin/bash

# Vérifie que le script a bien été appelé avec 1 argument
# - Le numéro de la règle
if [ "$#" -ne 1 ]
then
	exit 1
fi

# Stockage du numéro de règle
rule_number=$1

# Suppression de la règle NAT
iptables -t nat -D PREROUTING "$rule_number"

exit 0
