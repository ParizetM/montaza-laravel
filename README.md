# Guide d'installation pour le projet Montaza

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [Git](https://git-scm.com/)
- [Node.js](https://nodejs.org/) (version 14 ou supérieure)
- [npm](https://www.npmjs.com/) (généralement inclus avec Node.js)
- [Composer](https://getcomposer.org/)
- [wkhtmltopdf](https://wkhtmltopdf.org/) (pour générer des PDF à partir de HTML)

## Étapes d'installation

1. **Cloner le dépôt**

    ```bash
    git clone https://github.com/votre-utilisateur/montaza.git
    cd montaza
    ```

2. **Installer les dépendances PHP**

    ```bash
    composer install
    ```

3. **Installer les dépendances JavaScript**

    ```bash
    npm install
    ```

4. **Configurer l'environnement**

    Copiez le fichier `.env.example` en `.env` et modifiez les paramètres nécessaires.

    ```bash
    cp .env.example .env
    ```

5. **Générer la clé de l'application**

    ```bash
    php artisan key:generate
    ```

6. **Migrer la base de données**

    Assurez-vous que votre base de données est configurée dans le fichier `.env`, puis exécutez :

    ```bash
    php artisan migrate
    ```

7. **Lancer le serveur de développement**

    ```bash
    php artisan serve
    ```




8. **Compiler pour la production**

  ```bash
  npm run build
  ```

    Vous pouvez maintenant accéder à l'application à l'adresse [http://localhost:8000](http://localhost:8000).
