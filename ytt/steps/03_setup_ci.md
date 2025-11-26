03 — Setup CI / GitHub Actions (build, optional migrate, deploy)
===============================================================

Goal
- Build the app artifacts and deploy to Azure Web App using the publish profile (safe for code-based deployments). Optionally run migrations only when DB secrets are available.

High level steps
1. Build PHP & Node assets on CI.
2. Upload/publish using the App Service publish profile (recommended for code deploys).
3. (Optional) Run database migrations if DB secrets are present.

Key repository file
- `.github/workflows/azure-deploy.yml` — edit or add this workflow.

Minimal `azure-deploy` job skeleton (example logic):

```yaml
name: Azure Deploy
on:
  push:
    branches: [ main ]

jobs:
  build-and-deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Set up PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install composer dependencies
        run: composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader
      - name: Install Node and build assets
        uses: actions/setup-node@v4
        with:
          node-version: '18'
      - run: npm ci && npm run build

      # Create .env from secrets only if DB secrets are present
      - name: Prepare .env
        if: ${{ secrets.DB_HOST && secrets.DB_DATABASE && secrets.DB_USERNAME && secrets.DB_PASSWORD }}
        run: |
          cat > .env <<'ENV'
          APP_ENV=production
          APP_KEY=${{ secrets.APP_KEY }}
          DB_CONNECTION=mysql
          DB_HOST=${{ secrets.DB_HOST }}
          DB_PORT=${{ secrets.DB_PORT }}
          DB_DATABASE=${{ secrets.DB_DATABASE }}
          DB_USERNAME=${{ secrets.DB_USERNAME }}
          DB_PASSWORD=${{ secrets.DB_PASSWORD }}
          ENV

      - name: Deploy to Azure WebApp
        if: ${{ secrets.AZURE_WEBAPP_PUBLISH_PROFILE }}
        uses: azure/webapps-deploy@v2
        with:
          publish-profile: ${{ secrets.AZURE_WEBAPP_PUBLISH_PROFILE }}
          package: '.'

      - name: Run migrations (optional)
        if: ${{ secrets.DB_HOST && secrets.DB_DATABASE && secrets.DB_USERNAME && secrets.DB_PASSWORD }}
        run: php artisan migrate --force

```

Notes & safety
- Do not store secrets in the repo. Add them as GitHub repository Secrets.
- Guard the migration step so it only runs when DB secrets are present to avoid failures on PRs or feature branches.
- Use `--force` only in controlled CI with backups in place.
