02 — Configure and test `startup.sh`
=====================================

Goal
- Verify `startup.sh` works on App Service and locally where possible; ensure runtime, extensions, and file permissions are correct.

What this script should do (check these in `startup.sh`)
- Install PHP extensions required by the app (pdo_mysql, gd, zip, mbstring, etc.).
- Run `composer install --no-dev --optimize-autoloader` (or `composer install` for dev).
- Build assets (`npm ci && npm run build`) if needed.
- Run `php artisan migrate --force` optionally (we recommend running migrations separately in a controlled step).

Local smoke test (on a Linux dev box or WSL). If you cannot run the exact script locally, test the core steps manually:

```powershell
# From repo root
php -v
composer install --no-interaction
php artisan key:generate
php artisan migrate --force --env=testing

# Optional: build assets
npm ci
npm run build

# Run Laravel's built-in server for a quick check
php artisan serve --host=127.0.0.1 --port=8000

# Visit http://127.0.0.1:8000 to verify the app boots
```

App Service specific checks
- App Service `Startup Command` should run `startup.sh` (this repo contains a `startup.sh` and `.deployment` file).
- In App Service `Configuration > General settings` set `Linux` and `PHP 8.2` runtime.
- Set `WEBSITES_ENABLE_APP_SERVICE_STORAGE` to `true` if your startup needs filesystem persistence.

Where to edit
- `startup.sh` in repo root — review and adapt to the App Service environment.
- `.deployment` — ensure it calls the startup script.

Troubleshooting
- Check `az webapp log tail --name <appName> --resource-group <rg>` to stream logs.
- If composer fails: ensure `COMPOSER_MEMORY_LIMIT=-1` is set in App Settings or exported in `startup.sh`.
