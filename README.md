# City Lunch - EC 03

## Présentation du projet

City Lunch est une application web développée avec Symfony dans le cadre de l'épreuve **EC 03 - Conception et développement Backend**.

L'application permet à des clients de créer un compte, de se connecter, de consulter une liste de produits et d'ajouter des produits dans un panier.

Le projet est basé sur une partie du contexte City Lunch, une entreprise proposant des plats et desserts du jour à ses clients.

## Périmètre du projet

Le sujet complet City Lunch décrit également des fonctionnalités liées aux commandes, aux livreurs, au gérant, au suivi des livraisons et à la gestion des stocks.

Cependant, dans le cadre de cette épreuve, le périmètre demandé concerne uniquement la partie client :

- création de compte client
- connexion et déconnexion
- affichage des produits disponibles
- ajout de produits dans un panier
- consultation du panier
- suppression d'un produit du panier
- vidage du panier.

Le processus complet de commande, le paiement, le suivi de livraison, la gestion des livreurs et l'interface gérant ne sont pas implémentés dans cette version.

## Fonctionnalités développées

### Client

- Création d'un compte client
- Connexion avec email et mot de passe
- Déconnexion
- Redirection après connexion vers la page des produits
- Redirection après déconnexion vers la page de connexion

### Produits

- Création de l'entité `Product`
- Ajout de produits de test avec des fixtures
- Affichage des produits disponibles sur une page Twig
- Distinction entre plats et desserts

### Panier

- Création de l'entité `Cart`
- Création de l'entité `CartItem`
- Ajout d'un produit au panier
- Augmentation de la quantité si le même produit est ajouté plusieurs fois
- Affichage du panier
- Calcul du total du panier
- Suppression d'une ligne du panier
- Vidage complet du panier

### Navigation

- Page d'accueil `/` redirigée vers `/products`
- Barre de navigation avec accès aux produits, au panier, à la connexion, à l'inscription et à la déconnexion

## Technologies utilisées

- PHP
- Symfony 7
- Twig
- Doctrine ORM
- MySQL 8
- MongoDB 7
- Docker
- Docker Compose
- Git
- GitHub

## Bases de données utilisées

Le projet utilise deux types de bases de données :

### MySQL

MySQL est utilisé pour stocker les données principales de l'application.

Exemples de données stockées dans MySQL :

- clients ;
- produits ;
- paniers ;
- lignes de panier.

MySQL est adapté ici car les données sont structurées et reliées entre elles. Par exemple, un panier appartient à un client, et une ligne de panier est liée à un produit.

### MongoDB

MongoDB est prévu pour le stockage des sessions utilisateur.

Les sessions sont des données techniques.

Cette séparation permet de distinguer :

- les données métier dans MySQL ;
- les données techniques de session dans MongoDB.


Installation du projet
1. Cloner le dépôt
git clone https://github.com/mael-donnadille/EC-03-City-lunch.git
cd EC-03-City-lunch
2. Installer les dépendances PHP
composer install
3. Lancer les conteneurs Docker
docker compose up -d

Les services Docker utilisés sont :

MySQL ;
MongoDB ;
Mailpit.
4. Configurer la base de données

Dans le fichier .env, la variable DATABASE_URL doit pointer vers MySQL.

Exemple utilisé dans le projet :

DATABASE_URL="mysql://city_lunch_user:city_lunch_password@127.0.0.1:3308/city_lunch?serverVersion=8.0.32&charset=utf8mb4"

Selon la configuration locale, le port peut être adapté si un autre service MySQL utilise déjà le port 3306.

5. Créer la base de données
php bin/console doctrine:database:create --if-not-exists
6. Exécuter les migrations
php bin/console doctrine:migrations:migrate
7. Charger les fixtures
php bin/console doctrine:fixtures:load

Cette commande ajoute des produits de test dans la base.

Exemples :

Poulet ;
Eau Plates ;
Salade ;
Tiramisu ;
Macarons à la vanille.
8. Lancer le serveur Symfony

Avec le serveur PHP intégré :

php -S 127.0.0.1:8000 -t public public/index.php

L'application est ensuite accessible à l'adresse :

http://127.0.0.1:8000
Routes principales
Route	Description
/	Redirection vers /products
/products	Liste des produits
/register	Création d'un compte client
/login	Connexion client
/logout	Déconnexion client
/cart	Affichage du panier
/cart/add/{id}	Ajout d'un produit au panier
/cart/remove/{id}	Suppression d'une ligne du panier
/cart/clear	Vidage du panier
Commandes utiles
Vérifier les routes
php bin/console debug:router
Vérifier la configuration de sécurité
php bin/console debug:config security firewalls.main
Vérifier le fichier YAML de sécurité
php bin/console lint:yaml config/packages/security.yaml
Vérifier la cohérence Doctrine
php bin/console doctrine:schema:validate
Vérifier les produits en base
php bin/console doctrine:query:sql "SELECT * FROM product"
Vérifier les clients en base
php bin/console doctrine:query:sql "SELECT id, email FROM customer"
Vérifier les paniers
php bin/console doctrine:query:sql "SELECT * FROM cart"
Vérifier les lignes de panier
php bin/console doctrine:query:sql "SELECT * FROM cart_item"
Gestion Git

Le projet a été versionné avec Git et GitHub.

Exemple d'organisation utilisée :

main
develop
feature/installation-symfony
feature/docker-databases
feature/entities
feature/product-fixtures
feature/display-products
feature/authentication
feature/cart
feature/readme-final

Chaque fonctionnalité importante a été développée dans une branche dédiée, puis fusionnée dans develop.

Stratégie de sauvegarde

Une stratégie de sauvegarde simple et adaptée au projet peut être mise en place.

Sauvegarde MySQL

MySQL contient les données métier importantes : clients, produits, paniers et futures commandes.

Il est recommandé de mettre en place :

une sauvegarde quotidienne de la base MySQL ;
une sauvegarde hebdomadaire conservée plus longtemps ;
une conservation des sauvegardes quotidiennes pendant 7 jours ;
une conservation des sauvegardes hebdomadaires pendant 1 mois ;
un stockage des sauvegardes sur un support externe ou distant ;
un test régulier de restauration.

Exemple de commande possible :

mysqldump -u city_lunch_user -p city_lunch > backup_city_lunch.sql
Sauvegarde MongoDB

MongoDB étant utilisé pour les sessions, les données sont moins critiques que les données métier.

Cependant, une sauvegarde peut être prévue avec :

mongodump --db city_lunch_sessions --out ./backup_mongodb

Comme les sessions sont temporaires, la priorité de sauvegarde reste MySQL.

Sauvegarde du code source

Le code source est sauvegardé via GitHub.

Il est recommandé de :

pousser régulièrement le code sur GitHub ;
utiliser des branches pour les fonctionnalités ;
conserver une branche main stable ;
conserver une branche develop pour l'intégration.
Recommandation SSL

Pour un déploiement en production, il est recommandé d'utiliser un certificat SSL afin de sécuriser les échanges entre le navigateur et le serveur.

Le SSL est important car l'application gère :

la création de compte ;
la connexion ;
les mots de passe ;
les sessions utilisateur.

Une solution adaptée serait d'utiliser Let's Encrypt avec Certbot sur un serveur Nginx ou Apache.

Exemple de mise en place possible :

sudo certbot --nginx -d city-lunch.example.com

ou avec Apache :

sudo certbot --apache -d city-lunch.example.com

Le certificat SSL permettrait d'utiliser l'application en HTTPS :

https://city-lunch.example.com
Recommandations IDE

Pour travailler confortablement sur le projet, il est recommandé d'utiliser Visual Studio Code avec les extensions suivantes :

PHP Intelephense ;
Symfony Support ;
Twig Language 2 ;
Docker ;
GitLens ;
YAML.
Évolutions possibles

Le projet peut être amélioré avec les fonctionnalités suivantes :

gestion complète des commandes ;
validation du panier ;
paiement en ligne ;
historique des commandes client ;
interface administrateur ;
interface gérant ;
création et gestion des comptes livreurs ;
affectation d'un livreur à une commande ;
suivi de livraison ;
géolocalisation des livreurs ;
gestion des stocks ;
appréciation des plats commandés.
Dépôt GitHub

Le code source du projet est disponible à l'adresse suivante :

https://github.com/mael-donnadille/EC-03-City-lunch

Ensuite enregistre le fichier, puis fais :

```powershell
git status
git add README.md
git commit -m "Rédaction du README final"
git push -u origin feature/readme-final

Puis fusionne dans develop :

git checkout develop
git pull origin develop
git merge feature/readme-final
git push origin develop

À la toute fin, tu pourras fusionner develop dans main :

git checkout main
git pull origin main
git merge develop
git push origin main