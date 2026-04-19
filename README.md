# Fournisseur d'Accès à Internet (FAI)

Bienvenue sur le dépôt de mon ***Fournisseur d'Accès à Internet (FAI)***, proposant une **interface web sur la box Internet permettant de paramétrer les services proposés de manière intuitive**.  

Le but du projet est de permettre à n'importe quel utilisateur de configurer sa box *Internet* selon ses besoins, sans avoir nécessairement de connaissances en informatique.  

Ce projet a été réalisé dans le cadre de l'**Activité de Mise en Situation (AMS) Réseau** tout au long de ma **troisième année de licence en informatique** à l'université d’*Avignon*.  

Ce travail m’a permis de mettre en pratique toutes les connaissances acquises en **réseaux informatiques**, en **scripting (*Bash*)** et en **développement web (*HTML*, *CSS*, *JavaScript*, *PHP*)**.

---

## Tableau de bord

Après s'être connecté sur le formulaire de connexion, l'utilisateur arrive alors sur le tableau de bord, permettant de rapidement voir les informations pertinentes des services proposés ainsi que d'accéder à des services secondaires comme la messagerie.

---

## Box

Cet onglet regroupe les fonctionnalités permettant de configurer la box Internet.

### Formulaire IP 

Le **formulaire IP** permet à l'utilisateur **de modifier l'adresse IP de la box *Internet* ainsi que le masque de sous-réseau, de manière interactive grâce à du *JavaScript***.  
Celui-ci ajuste automatiquement les octets modifiables de l'adresse IP en fonction du masque de sous-réseau.  

### Nom de domaine de la box

Il est également possible de configurer un nom de domaine pour la box Internet, du style `[PRÉNOM].ceri.com`. Ce formulaire ira modifier la ligne dans le fichier de configuration DNS du FAI pour ajuster le prénom.

---

## Réseau

Le **formulaire DHCP** affiche automatiquement, à l’ouverture, **le nombre d’hôtes actuellement configurés**.  
Ces informations sont calculées à partir de l’adresse IP et du masque de sous-réseau, ce qui permet également de déterminer l’adresse réseau, le CIDR et le nombre maximal d’hôtes possibles.  
L’utilisateur peut alors **saisir un nouveau nombre d’hôtes** afin de définir une nouvelle plage d’adresses que le serveur DHCP attribuera automatiquement.  

### Mode avancé

Pour les utilisateurs du mode avancé, ils ont la possibilité de créer leur propre plage d'adresses IP sans qu'on leur demande le nombre d'appareils souhaités, c'est eux qui choisissent l'adresse de départ et celle d'arrivée.

---

## Appareils

Cet onglet a pour but d'afficher les appareils connectés à la box, c'est à dire les appareils qui ont reçu une adresse IP de la part du serveur DHCP de la box Internet.  

Il est également possible sur cet onglet de configurer un sous-domaine pour un appareil de la forme `[PRÉNOM].[PRÉFIXE DU NOM DE DOMAINE DE LA BOX].ceri.com`.  

---

## Sécurité

Un onglet primordial pour une box Internet puisque tout le traffic vers Internet, qu'il soit entrant ou sortant, passe par la box Internet.  

L'utilisateur peut activer / désactiver le pare-feu, configuré avec une politique de sécurité comprenant plusieurs règles de base.  

### Mode avancé 

Le mode avancé permet de configurer du port-forwarding pour associer un port de la box Internet et un protocole à une adresse IP et un port.

### Contrôle parental

Un contrôle parental a été mis en place permettant à un parent de créer un profil pour son enfant (un profil associe un enfant (âge) à un appareil grâce à son adresse IP).

Une fois le profil crée, une grid s'affiche representant le planning de contrôle parental du profil. On peut très facilement autoriser (vert) l'accès à Internet pour cet appareil à un jour et une heure donnée, ou le refuser (rouge) grâce à un clic sur la case correspondante. Pour confirmer les changements, il faudra cliquer sur le bouton de sauvegarde.

#### IA Contrôle parental

Puisque c'est très long de configurer sois-même chaque case du contrôle parental, j'ai décidé de mettre en place une IA permettant à un utilisateur lorsqu'il crée un profil, de cocher ou non la case IA. Si elle est cochée, alors l'âge du profil à crée sera comparé aux plannings de contrôle parental des profils déjà crées. Grâce à un système de poids (plus l'âge sera proche, plus le poids sera fort) on pourra généré la grid.  

---

## Forum 

Cet onglet permet à un utilisateur de créer une discussion (titre + message) et de participer à une discussion en envoyant un message (il est possible de supprimer un message mais pas de le modifier).  

### IA Forum 

Le but de cet IA est de : 
- éviter de congestionner la BDD (moins d'enregistrements car moins de discussions similaires),
- moins de travail pour les potentiels employés du FAI (qui dans mon projet, ont un compte, et une fois connectés lorsqu'ils envoient un message leur message est certifié d'un badge), leur permettant de se concentrer sur d'autres tâches,
- permettre à l'utilisateur d'obtenir une réponse plus rapidement si une discussion propose déjà la solution au problème.

Pour cela, j'ai utilisé un système de one hot vector pour représenter dans un vecteur binaire si le mot est présent ou non dans le vocabulaire (initialisé par moi-même avec selon moi les mots les plus suceptibles d'être utilisés).

#### Apprentissage par renforcement

Pour améliorer mon IA, j'ai mis en place un apprentissage par renforcement pour qu'elle rajoute dans son vocabulaire, les mots qui ne sont pas déjà dans le vocabulaire, mais qui ne sont pas non plus dans une **stop list** récupérée sur Internet, pour éviter de stocker dans le vocabulaire des mots inutiles comme les déterminants, les pronoms personnels, etc...

Ainsi, lorsqu'un utilisateur utilise un nouveau mot, il sera inséré dans le vocabulaire, tous les vecteurs binaires de tous les titres et discussions seront alors recalculés pour que l'ajout et l'apprentissage soit effectif.

--- 

## Paramètres

C'est dans cet onglet que l'utilisateur peut modifier son nom d'utilisateur, sont mot de passe, et surtout le plus intéressant ici, son mode de configuration (avancé ou débutant).