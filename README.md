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
docker-compose up --build
```

Then, in another terminal, run initial setup inside container:

```bash
docker-compose exec app composer install
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

The app will be available at `http://localhost:8000`.

Container reads Laravel `.env` directly from the mounted project root (`./:/var/www/html`).
Configure DB only in the project `.env` via `DB_*` variables.
`docker-compose.yml` does not start a DB service and does not override `DB_*`.

## Swagger UI

- UI: `http://localhost:8000/swagger`
- Spec URL: `http://localhost:8000/swagger/openapi.json`
- Spec file on disk: `resources/swagger/openapi.json`

## Tests

Run test suite:

```bash
php artisan test
```

Tests run with `APP_ENV=testing` and load `.env.testing`.
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
