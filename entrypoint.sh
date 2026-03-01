#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"
DB_WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-60}"

log() {
    echo "[entrypoint] $1"
}

cd "$APP_DIR"

git config --global --add safe.directory "$APP_DIR" || true

log "Installing Composer dependencies (with require-dev)..."
composer install --no-interaction --prefer-dist --no-progress

if [[ ! -f .env ]]; then
    log "ERROR: .env not found in project root ($APP_DIR/.env)."
    log "Mount your project with a valid .env file before starting the container."
    exit 1
fi

app_key_line="$(grep -E '^APP_KEY=' .env | tail -n1 || true)"
app_key_value="${app_key_line#APP_KEY=}"

if [[ -z "${app_key_value}" ]]; then
    log "APP_KEY is empty. Generating a new key..."
    php artisan key:generate --force
else
    log "APP_KEY is already set."
fi

log "Clearing Laravel config..."
php artisan optimize

log "Waiting for database connection (timeout: ${DB_WAIT_TIMEOUT}s)..."
db_ready=0
for ((i = 1; i <= DB_WAIT_TIMEOUT; i++)); do
    if php -r "require 'vendor/autoload.php'; \$app=require 'bootstrap/app.php'; \$kernel=\$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); try { \$app['db']->connection()->getPdo(); } catch (Throwable \$e) { fwrite(STDERR, \$e->getMessage()); exit(1); }" >/dev/null 2>&1; then
        db_ready=1
        break
    fi
    sleep 1
done

if [[ "$db_ready" -ne 1 ]]; then
    log "ERROR: Database is not reachable after ${DB_WAIT_TIMEOUT}s."
    log "Check DB_* values in .env and ensure external MySQL is accessible."
    exit 1
fi
log "Database is reachable."

log "Running migrations..."
php artisan migrate --force

log "Running test suite..."
php artisan test

log "Generating Swagger..."
php artisan l5-swagger:generate

log "Starting web server on 0.0.0.0:8000..."
exec php artisan serve --host=0.0.0.0 --port=8000
