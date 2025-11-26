# Implementation Plan — Laravel on Azure (from video)

This document converts the tutorial transcript into a concise, actionable implementation plan mapped to files in this repository. Use this as a checklist for automating deployment to Azure Web Apps with MySQL and Laravel queues.

Goal
- Deploy the `improved-barnacle` Laravel app to Azure Web Apps (Linux) using Code publish, connect to Azure Database for MySQL, and enable background queue processing. Build assets and run migrations as part of CI/CD.

Priority Tasks (short list)
1. Prepare Azure resource automation (Resource Group, App Service Plan, Web App, MySQL Flexible Server).
2. Ensure startup and runtime configuration in `startup.sh` is correct for Azure App Service.
3. Harden and update GitHub Actions workflow to deploy (populate `.env` from secrets, optional guarded migrations, deploy with publish profile).
4. Document GitHub Secrets and Azure steps in `QuickStart/Azure/` and `ytt/`.
5. Optionally: add queue worker guidance (App Service WebJobs or separate worker App Service) and health-check endpoints.

Detailed steps and file mapping

Step A — Prepare Azure resource automation (optional, but recommended)
- What: Script resource creation via `az` CLI to avoid manual portal steps.
- Actions:
  - Create `scripts/azure_create_resources.ps1` (PowerShell) or `scripts/azure_create_resources.sh` (Bash) that accepts variables: resource group, location, app plan name, webapp name, mysql server name, admin user, admin password (or reference Key Vault).
  - The script will:
    - `az group create`
    - `az appservice plan create --is-linux --sku B1`
    - `az webapp create --resource-group RG --plan PLAN --name WEBAPP --runtime "PHP|8.1"`
    - `az mysql flexible-server create` (or `az mysql server create`) and set firewall rule to allow web app.
  - Deliverable files: `scripts/azure_create_resources.ps1`, `QuickStart/Azure/CREATE_RESOURCES.md`.

Step B — Startup script validation
- What: Make sure `startup.sh` does everything the video shows and is safe for App Service.
- Files to review/edit: `startup.sh`, `.deployment`, `web.config`.
- Required behaviors:
  - Run `composer install --no-dev --optimize-autoloader`
  - Ensure `APP_KEY` exists (`php artisan key:generate --force` when needed)
  - Run `php artisan migrate --force` only when DB credentials are present and optionally behind a guard environment flag to avoid accidental migrations in dev.
  - Build front-end assets `npm ci && npm run build`
  - Set correct permissions for `storage` and `bootstrap/cache`
  - Start PHP-FPM / configure Nginx (App Service gives runtime; include only necessary customizations)

Step C — CI/CD workflow updates
- What: Update `.github/workflows/azure-deploy.yml` so workflow:
  - Builds PHP and Node assets
  - Optionally writes `.env` from GitHub Secrets (DB_* secrets)
  - Runs migrations only if `DB_HOST` (or a boolean secret) is present
  - Deploys using `azure/webapps-deploy@v2` with `AZURE_WEBAPP_PUBLISH_PROFILE` secret
- Files to edit: `.github/workflows/azure-deploy.yml` (already exists). Suggested changes:
  - Add `env:` for `DB_` secrets and guard `php artisan migrate --force` with `if` condition.
  - Add a step to create `.env` before build (use a template or `cp .env.example .env` then write values from secrets)
  - Keep deploy step conditional if `AZURE_WEBAPP_PUBLISH_PROFILE` is not set to avoid workflow errors.

Step D — Secrets & configuration guidance
- What: Document which secrets need adding to GitHub and App Service settings.
- Required secrets (GitHub repo):
  - `AZURE_WEBAPP_PUBLISH_PROFILE` (publish profile XML)
  - Optional CI DB secrets: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- App Service Application settings (recommended for runtime):
  - Add the same DB_* keys in the Web App Configuration so App Service can use them at runtime without embedding in code.
- Files to update: `QuickStart/Azure/DEPLOYMENT_GUIDE.md` and `QuickStart/Azure/CHECKLIST.md`.

Step E — Queue worker / background jobs
- What: Provide recommended approaches and scripts:
  - Option 1: Use a separate Web App (or App Service Plan) running `php artisan queue:work` as a background service (configure always-on) — add a deployment slot or dedicated worker.
  - Option 2: Use Azure Functions or WebJobs to trigger queue workers.
- Files to add: `QuickStart/Azure/QUEUE_WORKER.md` with concrete commands and sample `systemd`/supervisor instructions (if using VM or container) or App Service guidance.

Step F — Testing and verification
- What: Health checks and verification steps after deployment.
- Steps to perform post-deploy:
  - Check Web App URL and home page.
  - Check log streaming and GitHub Actions logs.
  - Run a smoke test hitting an endpoint that reads/writes to DB and enqueues a job.
  - Verify queue worker processes the job.

Branching and commits
- Suggested branch name: `azure/deploy-automation`
- Commit structure (small, focused commits):
  1. `chore(azure): add scripts/azure_create_resources.ps1` (resource automation)
  2. `chore(azure): update startup.sh to guard migrations and prepare runtime` (startup changes)
  3. `ci(azure): update azure-deploy workflow to populate .env and guard migrations` (workflow changes)
  4. `docs(azure): update QuickStart/Azure with secrets and deployment steps` (docs)

What I can implement for you now (pick from these):
- (A) Create `scripts/azure_create_resources.ps1` with parameterized `az` commands.
- (B) Update `.github/workflows/azure-deploy.yml` to populate `.env` from secrets and guard migrations.
- (C) Tighten `startup.sh` to be idempotent and safe for App Service.
- (D) Create PR-ready commits on a feature branch containing the chosen items.

Verification commands (run locally)
- Create resources (if using script): `.	ools\run-azure-setup.ps1 -ResourceGroup my-rg -WebAppName my-webapp -MysqlAdminPwd 'P@ssw0rd'` (example; exact script args will be provided).
- After push: monitor Actions tab and check the deployment logs. Use `az webapp log tail --name <app> --resource-group <rg>` to stream logs.

Security notes
- Never store DB passwords in plain files in the repo. Use GitHub Secrets or App Service Application settings. Consider Azure Key Vault for production secrets.

Next step for me (if you approve)
- I will implement option (A) or (B) or both on a feature branch, run the local tests I can, and produce a PR with instructions to run the `az` commands and set the required secrets.
