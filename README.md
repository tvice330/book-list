# Book LIST REST API (Laravel 12)

REST API for managing books with CRUD endpoints, migrations, seeders, tests, and Swagger UI.

## Requirements

- PHP 8.2+ (project runtime), Docker image uses PHP 8.5 CLI
- Composer
- External database (MySQL/PostgreSQL/etc.) for normal runtime

## API Endpoints

- `GET /api/books` - list books (paginated)
- `POST /api/books` - create book
- `GET /api/books/{id}` - show book
- `PATCH /api/books/{id}` - partial update
- `DELETE /api/books/{id}` - delete book

## Book Fields

- `title` (string)
- `publisher` (string)
- `author` (string)
- `genres` (json array of strings)
- `published_at` (date)
- `word_count` (unsigned integer)
- `price_usd` (decimal 10,2)

## Local Setup

1. Install dependencies:

```bash
composer install
```

2. Create env file:

```bash
cp .env.example .env
```

3. Generate app key:

```bash
php artisan key:generate
```

4. Configure external DB in `.env` (`DB_*` values).

5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Start server:

```bash
php artisan serve
```

App URL: `http://127.0.0.1:8000`

## Docker Setup (No DB container)

Build and run:

```bash
docker compose up --build
```

On container start, `entrypoint.sh` runs all bootstrap steps automatically:

- `composer install` (with `require-dev`)
- checks `.env` exists in project root
- generates `APP_KEY` when empty
- clears config/cache
- waits for external DB availability
- `php artisan migrate --force`
- `php artisan test`
- `php artisan l5-swagger:generate`
- starts server: `php artisan serve --host=0.0.0.0 --port=8000`

The app will be available at `http://localhost:8000`.

Container reads Laravel `.env` directly from the mounted project root (`./:/var/www/html`).
Configure DB only in the project `.env` via `DB_*` variables.
`docker-compose.yml` does not start a DB service and does not set DB credentials (`DB_DATABASE/DB_USERNAME/DB_PASSWORD`).
For Docker mode, the container uses `DB_HOST=host.docker.internal` (Linux via `host-gateway`) to access MySQL running on the host.
You can keep local `.env` as `DB_HOST=127.0.0.1` for non-Docker runs.
External MySQL must be reachable from the container before startup completes.
If any bootstrap step fails, the container exits with an error and the web server is not started.

After startup, you can verify Laravel inside the running container:

```bash
docker compose exec app php artisan --version
```

## Swagger UI

- UI: `http://localhost:8000/swagger`
- Generate docs: `php artisan l5-swagger:generate`
- Generated JSON: `storage/api-docs/api-docs.json`
- Swagger documentation is generated from PHP OpenAPI annotations (`swagger-php` + `l5-swagger`).

## Tests

Run test suite:

```bash
php artisan test
```

`php artisan test` runs both Feature and Unit tests.

Tests run with `APP_ENV=testing` and load `.env.testing` (or your testing environment setup, if different).
Create a dedicated MySQL database (for example `book_list_test`) before running tests.
Grant your DB user access to `book_list_test` and set credentials in `.env.testing`.
Feature tests use the configured MySQL connection and wrap each test in a transaction with automatic rollback.
Database schema is created once per test process (`migrate:fresh`), not before every single test.

## Suggested commit history

- chore: init project / align to Laravel 12
- docs: add Readme.md describe
- feat: add books database setup
- feat: add books CRUD API
- test: add books API feature tests, run test's
- docs: add and test Swagger UI
- chore: add and test Docker setup
