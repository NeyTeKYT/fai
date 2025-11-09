# 🌐 Fournisseur d'Accès à Internet (FAI)

Bienvenue sur le dépôt du ***Fournisseur d'Accès à Internet (FAI)***, une **interface web permettant de paramétrer les services proposés par un fournisseur d'accès à *Internet* sur un serveur, de manière intuitive**.  

Le but de projet est de permettre à n'importe quel utilisateur de configurer sa box *Internet* selon ses besoins, sans avoir nécessairement de connaissances en informatique.  

Ce projet a été réalisé dans le cadre de l'**Activité de Mise en Situation (AMS) Réseau** tout au long de ma **troisième année de licence en informatique** à l'université d’*Avignon*.  

🔍 Ce travail m’a permis de mettre en pratique toutes les connaissances acquises en **réseaux informatiques**, en **scripting (*Bash*)** et en **développement web (*HTML*, *CSS*, *JavaScript*, *PHP*)**.

---

## 📮 Formulaire IP

La première étape de ce projet a consisté à créer, sur l'interface web, un formulaire permettant **de modifier l'adresse IP de la box *Internet***.

Pour cela, j'ai développé un **script *Bash*** (`/scripts/ip.sh`) qui :
1. Vérifie le nombre d'arguments fournis lors de l'appel du script et s'interrompt s'il n'y en a pas 2 (l'adresse IP et le masque de sous-réseau).
2. Récupère, dans le fichier `/etc/network/interfaces`, l'adresse IP et le masque de sous-réseau de la configuration actuellle.
3. Vérifie que les valeurs saisies par l'utilisateur sont différentes de la configuration actuelle (pour éviter une exécution inutile du script).
4. Remplace l'adresse IP et le masque de sous-réseau par ceux fournis par l'utilisateur.
5. Crée un dossier de sauvegarde dans `/var/backups/FAI` s'il n'existe pas déjà.
6. Copie le fichier `/etc/network/interfaces` modifié dans `/var/backups/FAI` avec un nom horodatée : `interfaces_$date`.
7. Redémarre l'interface réseau et vide le cache.

L'interface web permet désormais d'exécuter ce script sans passer par le terminal.  
Pour le moment, elle dispose d'une simple page d'accueil avec un design CSS basique.  
Un menu de navigation permet d'accéder à la page d'accueil et aux différents formulaires.  

Le **formulaire IP** permet à l'utilisateur **de modifier l'adresse IP de la box *Internet* ainsi que le masque de sous-réseau, de manière interactive grâce à du *JavaScript***.  
Celui-ci ajuste automatiquement les octets modifiables de l'adresse IP en fonction du masque de sous-réseau.  

Lors de la soumission du formulaire, le serveur PHP (une migration partielle vers JavaScript pour l'alléger est prévue) effectue plusieurs vérifications :
- Analyse chaque octet du masque de sous-réseau saisi et s'assure qu'il correspond à une valeur valide. En cas d'erreur, un message est affiché.
- Vérifie que le masque de sous-réseau est bien **consécutif en binaire** (une suite de 1 suivie d'une suite de 0). Sinon, un message d'erreur est affiché, car cette propriété est essentielle pur définir l'adresse réseau.
- Récupère l'adresse IP saisie par l'utilisateur
- Vérifie, grâce à la fonction *filter_var*, qu'elle est au format IPv4 et qu'il s'agit bien d'une **adresse privée** (non routable, car utilisée dans un réseau interne sous VirtualBox).
- Exécute le script `/scripts/ip.sh` précédemment crée, via la fonction *exec*, en lui transmettant l'adresse IP et le masque de sous-réseau saisis.
- Vérifie le code de retour du script pour déterminer si la configuration est identique à l'actuelle.
- Affiche un message d'erreur dans ce cas, ou un message de confirmation en cas de succès.

![demo_formulaire_ip](https://github.com/user-attachments/assets/7777c35d-6986-4d61-8c81-01fe4fec9bcc)

---

## 📮 Formulaire DHCP

La deuxième étape de ce projet a consisté à créer, sur l'interface web, un formulaire permettant **de modifier la plage d'adresses attribuées par le serveur DHCP.**  

Pour cela, j'ai développé un **script *Bash*** (`/scripts/dhcp.sh`) qui :
1. Vérifie le nombre d'arguments fournis lors de l'appel du script et s'interrompt s'il n'y en a pas 2 (le nombre d'appareils souhaités dans la plage d'adresses et l'adresse réseau).
2. Stocke ces arguments dans des variables.
3. Récupère, dans le fichier `/etc/network/interfaces`, l'adresse IP et le masque de sous-réseau de la configuration actuellle, puis les enregistre dans des variables.
4. Calcule le **CIDR** (nombre de bits à 1 dans le masque de sous-réseau). Si celui-ci vaut 31 ou 32, une erreur est renvoyée, car le protocole DHCP ne pourrait pas définir de plage d'adresses valides (ces masques ne laissent aucune adresse disponible pour les hôtes).
5. Calcule le **nombre maximal d'hôtes possibles**. Retourne une erreur si le nombre saisi par l'utilisateur dépasse cette limite.
6. Définit automatiquement **la première et la dernière adresse IP de la plage**, en fonction du nombre d'hôtes souhaité.
7. Réecrit le fichier de configuration DHCP avec les nouvelles valeurs.
8. Redémarre le serveur DHCP pour appliquer les changements.

L’interface web permet désormais d’ajuster dynamiquement la plage d’adresses DHCP sans avoir à modifier les fichiers de configuration manuellement.  
Comme pour le formulaire IP, la page est accessible depuis le menu de navigation et bénéficie du même design CSS simple et cohérent.  

Le **formulaire DHCP** affiche automatiquement, à l’ouverture, **le nombre d’hôtes actuellement configurés**.  
Ces informations sont calculées à partir de l’adresse IP et du masque de sous-réseau, ce qui permet également de déterminer l’adresse réseau, le CIDR et le nombre maximal d’hôtes possibles.  
L’utilisateur peut alors **saisir un nouveau nombre d’hôtes** afin de définir une nouvelle plage d’adresses que le serveur DHCP attribuera automatiquement.  

Lors de la soumission du formulaire, le serveur PHP (une migration partielle vers JavaScript est également prévue pour améliorer les performances) effectue plusieurs vérifications :
1. Vérifie que le **nombre d’hôtes** saisi est bien un entier.
2. S’assure qu’il est **différent du nombre d’hôtes actuellement configurés**, et **inférieur au nombre maximal d’hôtes possibles** selon le masque de sous-réseau.
3. Exécute le script *Bash* `/scripts/dhcp.sh` précédemment créé via la fonction *exec*, en lui transmettant les valeurs saisies par l’utilisateur.
4. Analyse le **code de retour du script** pour détecter d’éventuelles erreurs.
5. Affiche un **message d’erreur ou de confirmation** selon le résultat.

![demo_formulaire_dhcp](https://github.com/user-attachments/assets/edccb038-e6d9-41e6-9097-4f2bfe1c51af)

---

## ⚙️ Installation

Ce projet a été conçu pour fonctionner sur des machines virtuelles.  
Voici les étapes d'installation du fournisseur d'accès à *Internet* sur votre serveur ou box :
1. Créez une machine virtuelle avec un système d'exploitation Linux (de préférence avec une interface graphique pour accéder à l'interface web depuis la même machine).
2. Configurez le fichier `/etc/network/interfaces` pour que l'interface "Réseau Interne" puisse obtenir une adresse IP de manière statique.
3. Clonez ce dépôt sur votre machine personnelle.   
4. Copiez le dossier `/scripts` dans votre répertoire personnel, par exemple : `/home/[Votre nom d'utilisateur]/`.
5. Installez un serveur Apache sur votre machine virtuelle.
6. Copiez le dossier `/interface_web` dans `/var/www/html/` afin d'y accéder via `localhost/interface_web` dans votre navigateur.
7. Modifiez les droits du dossier `/interface_web` : `sudo chmod 777 interface_web`
8. Pour permettre à Apache d'exécuter le script `/scripts/ip.sh` avec les privilèges sudo sans demander de mot de passe, exécutez `sudo visudo`, puis ajoutez la ligne suivante : `www-data ALL=(ALL) NOPASSWD: /home/[Votre nom d'utilisateur]/scripts/ip.sh`
9. Téléchargez le serveur DHCP avec la commande `sudo apt install isc-dhcp-server`.
10. Ajoutez une nouvelle ligne avec `sudo visudo` pour permettre à Apache d'exécuter le script sans demander de mot de passe : `www-data ALL=(ALL) NOPASSWD: /home/[Votre nom d'utilisateur]/scripts/dhcp.sh`.

Si tout est bien configuré, vous devriez pouvoir utiliser l'interface web pour modifier l'adresse IP du réseau interne et configurer la plage d'adresses attribuées par le serveur DHCP.

