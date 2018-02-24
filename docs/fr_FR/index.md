## Présentation

Ce plugin permet d’utiliser facilement l’application android **JPI**. POur utiliser ce plugin, il faut au minimum utiliser la version 0.92 de l’APK et la version 3 de Jeedom.
Il intégre un assistant de création/modification de commande.

Il dispose aussi d’un widget (dashboard/mobile) d’état et de contrôle de la partie multimédia avec 4 bouttons pré-définis
widget

Petit plus : il est compatible avec la fonction ASK !


## Installation

### Pré-requis
Installer PAW server et l’APK JPI sur votre périphérique android

### Comment faire ?
Vous pouvez vous aider du tuto de Guillaume : https://guillaumebraillon.fr/jeedom-installation-et-configuration-de-jeedom-paw-interface/



## Configuration
### Equipement

**Partie 1**

Informations à renseigner pour le bon fonctionnement du plugin :

**Adresse IP :** adresse IP de l’équipement JPI

**Port :** port de connexon de l’équipement JPI

**Preset média  à 4 :** pour les médias (type webradio ou autre) qui pourront être directement lancés depuis le widget

**Partie 2**

Commandes liées au widget

**Partie 3**

Commandes pour vos besoins

**Partie 4**

Bouton pour rafraichir le fichier de configuration JPI (par exemple, suite à une mise à jour de l’APK)

**Partie 5**

Bouton pour ouvrir un modal afin d’accèder directement à la configuration de l’APK JPI (Ne fonctionne uniquement depuis une connexion interne)

### Utilisation

Pour ajouter une commande, il faut aller dans l’onglet commande, ensuite vous avez le choix entre utiliser l’assistant de création de commande (bouton 9) ou de rentrer manuellement la commande désirée par copier/coller depuis l’APK JPI (bouton 1)
**Bouton 2**

Permet de tester la commande

**Bouton 3**

Permet de lancer l’assistant de modification de commande

**Menu déroulant 4**

Permet de sélection le type de la commande (utile lors de la création de commande de type info)

**Champ 5**

Nom de la commande

**Champ 6**

Action JPI

**Champ 7**

Paramètres obligatoires

**Champ 8**

Paramètres optionnelles

### Réglage du cron

Pour régler la fréquence fréquence de recuperation des données, il faut séléctionner le cron dans la partie configuration du plugin
cron




