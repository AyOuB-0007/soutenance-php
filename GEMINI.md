# Project Overview

This is a Symfony 8.0 project designed for restaurant management. It's built with PHP 8.4+ and utilizes Doctrine ORM for database interactions. The project currently features:

*   **Database Schema:** A comprehensive schema defining entities such as `Table`, `Reservation`, `Menu`, `ArticleMenu`, `Commande`, `ArticleCommande`, `Personnel` (serving as the user entity), and `SuiviCuisine`.
*   **Web Interface Basics:** A foundational web interface including:
    *   A responsive sidebar for navigation.
    *   An authentication system (login/logout) using the `Personnel` entity for user management.
    *   A basic home page (`/`) to welcome users.
*   **Asset Management:** Configured to use Symfony AssetMapper for CSS and JavaScript assets, although the `AssetMapperBundle` registration in `config/bundles.php` is currently commented out to resolve a "Class not found" error during application boot.
*   **Routing:** Core application routes (`/`, `/login`, `/logout`) are explicitly defined in `config/routes.yaml` as automatic annotation discovery was experiencing issues.

# Building and Running

## Dependencies

The project relies on Composer for dependency management.

*   **Install/Update Dependencies:**
    ```bash
    composer install
    ```
    or, to update existing dependencies:
    ```bash
    composer update
    ```

## Database Management (Doctrine ORM)

This project uses Doctrine ORM for database interactions and migrations. The `DATABASE_URL` is configured in `.env`.

*   **Generate Database Migrations:**
    After making changes to Doctrine entities, generate a new migration file:
    ```bash
    php bin/console make:migration
    ```
*   **Apply Database Migrations:**
    To update your database schema according to the generated migrations:
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

## Running the Application

### Using Symfony CLI (Recommended)

If you have the Symfony CLI installed, you can run the application with:
```bash
symfony serve
```
This command starts a local web server and opens your application in the browser.

### Using PHP's Built-in Web Server

Alternatively, you can use PHP's built-in web server:
```bash
php -S 127.0.0.1:8000 -t public
```
Then, access your application at `http://127.0.0.1:8000`.

# Development Conventions

*   **PHP Version:** Requires PHP 8.4 or higher.
*   **Coding Standards:** Adheres to Symfony's coding standards.
*   **Entity Naming:** Entities (`App\Entity\Table`, `App\Entity\Personnel`, etc.) and their repositories (`App\Repository\TableRepository`, etc.) are located in `src/Entity/` and `src/Repository/` respectively.
*   **Templating:** Uses Twig (`.html.twig` files in the `templates/` directory).
*   **Security:** Symfony Security component is configured to use the `App\Entity\Personnel` entity for user authentication.
*   **Routing:** Routes are manually defined in `config/routes.yaml` to ensure proper loading. New controllers will require manual route entries.
*   **Asset Management:** Assets (CSS, JS) are processed via Symfony AssetMapper. Custom styles are in `assets/styles/`, and are imported into `assets/styles/app.css`.

# Current State & Known Issues

*   **AssetMapperBundle Issue:** The `Symfony\Component\AssetMapper\AssetMapperBundle` is commented out in `config/bundles.php` due to a "Class not found" error. This was a temporary workaround to get the Symfony console functioning. It may need to be re-enabled and properly configured or upgraded if full AssetMapper functionality is required.
*   **Route Auto-Discovery:** Automatic discovery of routes from controller annotations (`#[Route]`) is currently not functioning as expected. Routes are manually defined in `config/routes.yaml`. This may indicate a deeper configuration issue with the Symfony setup.
