Back-end (Symfony)
Le dossier "src" contient les fichiers du back-end de notre application développée avec Symfony. Voici une brève explication de chaque sous-dossier :

Controller
Le dossier "Controller" contient les fichiers de contrôleurs qui sont responsables de la logique de traitement des requêtes HTTP. Les contrôleurs reçoivent les requêtes des utilisateurs, effectuent les opérations nécessaires (accès à la base de données, calculs, etc.) et renvoient les réponses appropriées.

Entity
Le dossier "Entity" contient les fichiers d'entités qui représentent les objets persistants dans notre base de données. Chaque entité est associée à une table dans la base de données et permet de manipuler les données à travers des objets dans notre application.

Form
Le dossier "Form" contient les fichiers de classes de formulaire qui sont utilisés pour créer et gérer les formulaires dans notre application. Les classes de formulaire définissent les champs, les validations et les actions associées aux formulaires, facilitant ainsi la collecte et la validation des données utilisateur.

Repository
Le dossier "Repository" contient les fichiers de classes de répertoire qui fournissent des méthodes pour interagir avec la base de données. Les répertoires permettent d'effectuer des opérations de recherche, de récupération, de création, de mise à jour ou de suppression de données dans la base de données.

Security
Le dossier "Security" contient les fichiers liés à la gestion de la sécurité de notre application. Vous trouverez ici des fichiers tels que "SecurityController" qui gèrent l'authentification des utilisateurs, l'autorisation d'accès aux pages protégées, la gestion des rôles et des permissions, etc.

Service
Le dossier "Service" contient les fichiers de classes de service qui encapsulent des fonctionnalités spécifiques et des opérations réutilisables de notre application. Les services sont responsables de tâches spécifiques qui peuvent être utilisées à plusieurs endroits dans notre application, favorisant ainsi la modularité et la réutilisabilité du code.