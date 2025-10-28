# 🍳 Cook Chef

Une application web à vocation de DEMO, de gestion de recettes de cuisine développée avec Symfony 7.3 et ses dernières fonctionnalités.

### 🔐 Gestion des utilisateurs

-   **Inscription** avec validation en temps réel via LiveComponents (démo technique des composants live components et dependent form fields)
-   **Vérification d'email** après inscription
-   **Authentification sécurisée** avec hashage des passwords
-   **Système de rôles** utilisateur, administrateur

### 📝 Gestion des recettes

-   **CRUD complet** des recettes
-   **Upload d'images** pour les miniatures de recettes
-   **Gestion des ingrédients et quantités** avec formulaires dynamiques (CollectionType)
-   **Validation avancée** avec contraintes personnalisées
-   **Système de permissions** avec Voters Symfony

### 🌐 Internationalisation

-   **Support multilingue** (français, anglais, allemand, espagnol, italien)
-   **Traduction des entités** via l'extension Doctrine Gedmo
-   **Interface adaptée** selon la locale de l'utilisateur

### 📊 Interface utilisateur

-   **Design responsive**
-   **Pagination** avec KnpPaginatorBundle
-   **Tri et filtres** pour les listes de recettes
-   **Composants interactifs** avec Symfony UX LiveComponents

### 📧 Communication

-   **Système de contact** avec envoi d'emails
-   **Notifications** par email pour les événements importants
-   **Interface de test d'emails** avec Mailpit

## 🛠️ Technologies utilisées

### Backend

-   **PHP 8.2+** avec Symfony 7.3
-   **Base de données** : PostgreSQL
-   **ORM** : Doctrine
-   **Authentification** : Composant Security de Symfony
-   **Validation** : Symfony Validator avec contraintes personnalisées

### Frontend

-   **Template Engine** : Twig
-   **CSS Framework** : Bootstrap 5.3.8
-   **JavaScript** : Stimulus avec Symfony UX
-   **Icons** : UX icon (initiative Symfony UX)
-   **Asset Management** : AssetMapper

### Développement

-   **Fixtures** : Doctrine Fixtures avec Faker
-   **Qualité de code** : PHPStan, PHP-CS-Fixer, SonarQube sur le repo GitHub

## 🚀 Installation

### Prérequis

-   PHP 8.2 ou supérieur
-   Composer
-   Docker et Docker Compose
-   Make (optionnel, pour les commandes simplifiées)

### Configuration

1. **Cloner le projet**

    ```bash
    git clone [url-du-repo]
    cd cook_chef
    ```

2. **Installer les dépendances**

    ```bash
    composer install
    ```

3. **Démarrer l'environnement Docker**

    ```bash
    docker-compose up -d
    ```

4. **Configurer la base de données**

    ```bash
    # Avec Make
    make generate-db

    # Ou manuellement
    php bin/console doctrine:database:create --if-not-exists
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    ```

5. **Installer les assets**
    ```bash
    php bin/console importmap:install
    php bin/console asset-map:compile
    ```

## 🔧 Utilisation

### Démarrage du serveur

```bash
symfony server:start
# ou
php -S localhost:8000 -t public/
```

### Accès aux services

-   **Application** : http://localhost:8000
-   **Interface d'administration** : http://localhost:8000/admin
-   **Mailpit (emails)** : http://localhost:8025
-   **Adminer (BDD)** : http://localhost:8080

### Comptes de test

-   **Admin** : admin@cook-chef.com / admin123
-   **Utilisateurs** : générés via les fixtures
