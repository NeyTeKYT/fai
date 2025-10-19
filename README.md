# 🌐 Fournisseur d'Accès à Internet (FAI)

Bienvenue sur le dépôt du ***Fournisseur d'Accès à Internet (FAI)***, une **interface web permettant de paramétrer les services proposés par un fournisseur d'accès à *Internet* sur un serveur, de manière intuitive**.  

Le but de projet est de permettre à n'importe quel utilisateur de configurer sa box *Internet* selon ses besoins, sans avoir nécessairement de connaissances en informatique.  

Ce projet a été réalisé dans le cadre de l'**Activité de Mise en Situation (AMS) Réseau** tout au long de ma **troisième année de licence en informatique** à l'université d’*Avignon*.  

🔍 Ce travail m’a permis de mettre en pratique toutes les connaissances acquises en **réseaux informatiques**, en **scripting (*Bash*)** et en **développement web (*HTML*, *CSS*, *JavaScript*, *PHP*)**.

---

## 📮 Formulaire IP

La première étape de ce projet a consisté à créer, sur l'interface web, un formulaire permettant **de modifier l'adresse IP du serveur**.  

Pour cela, j'ai développé un **script *Bash*** (`/scripts/ip.sh`) qui :
1. Vérifie le nombre d'arguments fournis lors de l'appel du script (utile notamment lorsqu'on l'exécute depuis le terminal).
2. Récupère dans le fichier `/etc/network/interfaces` l'adresse IP actuellle et la stocke dans une variable.
3. S'assure que l'adresse IP choisie par l'utilisateur n'est pas identique à la configuration actuelle.
4. Remplace l'adresse IP par celle choisie par l'utilisateur.
5. Crée un dossier de sauvegarde dans `/var/backups/FAI` s'il n'existe pas déjà.
6. Copie le fichier `/etc/network/interfaces` dans `/var/backups/FAI` avec un nom horodatée : `interfaces_$date`.
7. Redémarre l'interface réseau et vide le cache.

L'interface web permet désormais d'exécuter ce script.  
Pour le moment, elle propose une simple page d'accueil avec un design CSS basique.  
Un menu de navigation permet de passer de la page d'accueil au **formulaire IP**.  

Le **formulaire IP** permet à l'utilisateur **de modifier son adresse IP**.  
Une fois le formulaire soumis, le serveur PHP (une migration partielle vers JavaScript est prévue) effectue plusieurs vérifications : 
- Récupère l'adresse IP saisie par l'utilisateur
- Vérifie, grâce à la fonction *filter_var*, qu'elle est au format IPv4 et qu'il s'agit bien d'une adresse privée (puisqu'il s'agit d'un réseau local "Réseau Interne" sous VirtualBox, l'adresse ne doit pas être routable).
- Fournit un message d'erreur si le champ est incorrect, afin d'indiquer les conditions à respecter.
- Sinon, exécute le script `/scripts/ip.sh` crée précédemment, via la fonction *exec*.
- Vérifie le code de retour du script pour déterminer si l'adresse IP fournie est identique à la configuration actuelle.
- Affiche une erreur dans ce cas, ou un message de confirmation en cas de succès.

---

## ⚙️ Installation

Ce projet a été conçu pour fonctionner sur des machines virtuelles.  
Voici les étapes d'installation du fournisseur d'accès à *Internet* sur votre serveur ou box :
1. Créez une machine virtuelle avec un système d'exploitation Linux (de préférence avec une interface graphique pour accéder à l'interface web depuis la même machine).
2. Clonez ce dépôt sur votre machine personnelle.   
3. Copiez le dossier `/scripts` dans votre répertoire personnel, par exemple : `/home/[Votre nom d'utilisateur]/`.
4. Installez un serveur Apache sur votre machine virtuelle.
5. Copiez le dossier `/interface_web` dans `/var/www/html/` afin d'y accéder via `localhost/interface_web` dans votre navigateur.
6. Modifiez les droits du dossier `/interface_web` : `sudo chmod 777 interface_web`
7. Pour permettre à Apache d'exécuter le script `/scripts/ip.sh` avec les privilèges sudo sans demander de mot de passe, exécutez `sudo visudo`, puis ajoutez la ligne suivante : `www-data ALL=(ALL) NOPASSWD: /home/[Votre nom d'utilisateur]/scripts/ip.sh`

Si tout est bien configuré, vous devriez utiliser l'interface web pour modifier l'adresse IP du réseau interne.

---

# 🚀 Améliorations prévues pour le formulaire IP

- 🎭 Ajout d'un champ `netmask` pour permettre à l'utilisateur de modifier le masque de sous-réseau et ajuster la taille du réseau.
- 🧮 Vérification de la cohérence entre l'adresse IP saisie et le masque de sous-réseau.
- ✂️ Découpage des quatre octets de l'adresse IP selon le masque afin de modifier uniquement la partie correspondant au sous-réseau.