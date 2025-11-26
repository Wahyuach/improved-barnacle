# Tutorial Step-by-step (extracted from video)

This file lists a concise, ordered set of steps to deploy the `improved-barnacle` Laravel app to Azure Web Apps, based on the video transcript. Each step includes which repository file(s) to edit or inspect.

1) Create Azure Resource Group and Web App
   - Action: Create a Resource Group and a Linux Web App (publish: Code) in your chosen region.
   - Repo mapping: none (cloud step). Recommended names go in `scripts/azure_create_resources.ps1` if you automate.

2) Create Azure Database for MySQL (Flexible Server)
   - Action: Create a MySQL Flexible Server (or provisioning method you prefer) and note connection info (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
   - Repo mapping: add values to App Service Application Settings or GitHub Secrets (see step 6). ` .env.example` already uses MySQL.

3) Configure Web App runtime and startup
   - Action: Set Web App runtime to PHP 8.x and configure a custom startup script.
   - Repo mapping: `startup.sh` (already present). Confirm the script runs: it should `composer install`, `php artisan key:generate`, `php artisan migrate --force` (optional), `npm build`, and set permissions.

4) Add App Settings for DB and queue (in Azure Portal)
   - Action: In the Web App Configuration → Application settings, add DB connection variables and any other secrets.
   - Repo mapping: `.env.example` (template). Alternatively, you can use GitHub Actions to write `.env` from secrets during build.

5) Create GitHub Actions workflow to build & deploy
   - Action: Create a workflow that checks out code, installs PHP/Node deps, builds assets, and deploys via publish profile.
   - Repo mapping: `.github/workflows/azure-deploy.yml` (present). Ensure it uses the `AZURE_WEBAPP_PUBLISH_PROFILE` secret. Optionally have it read DB secrets to run migrations.

6) Add GitHub repository secrets
   - Required: `AZURE_WEBAPP_PUBLISH_PROFILE` (publish profile XML). Optional: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` for CI migrations.
   - Repo mapping: GitHub UI; the workflow can reference these secrets.

7) (Optional) Configure background workers / queues
   - Action: For Laravel queues, set up Supervisor on App Service or use Azure WebJobs/Containers.
   - Repo mapping: Document in `QuickStart/Azure/` or create scripts to install/configure workers.

8) Deploy and verify
   - Action: Trigger GitHub Actions (push to `main`), check logs, open your site, and verify the database and queues work.
   - Repo mapping: `startup.sh`, `web.config`, GitHub Actions logs, Azure Portal.

Files you should review now in this repo:
- `startup.sh` — startup tasks executed on App Service boot.
- `.github/workflows/azure-deploy.yml` — CI/CD workflow.
- `.deployment` and `web.config` — App Service deployment hints.
- `QuickStart/Azure/*` — documentation and guides.
- `ytt/` — transcript and helper scripts (transcript generation & analysis).

Next recommended repo actions (optional):
- Add `scripts/azure_create_resources.ps1` to automate resource creation with `az` CLI (remember to secure passwords).
- Update the workflow to populate `.env` from GitHub secrets if you want migrations to run automatically.
- Add tests or health-check endpoints to verify deployment success.
