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

## Swagger UI

- UI: `http://localhost:8000/swagger`
- Spec file: `resources/swagger/openapi.json`

Swagger route is enabled in local environment.

## Tests

Run test suite:

```bash
php artisan test
```

Tests use SQLite in-memory DB (`phpunit.xml`):

- `DB_CONNECTION=sqlite`
- `DB_DATABASE=:memory:`

## Suggested commit history

- chore: init project / align to Laravel 12
- feat: add books database setup
- feat: add books CRUD API
- test: add books API feature tests
- docs: add Swagger UI
- chore: add Docker setup
- ci: add GitHub Actions tests
