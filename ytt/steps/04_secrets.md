04 — Secrets and publish profile
================================

Required secrets for the recommended flow
- `AZURE_WEBAPP_PUBLISH_PROFILE` — the publish profile XML for the App Service (used by `azure/webapps-deploy`).
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — database connection details if you want CI to run migrations.
- `APP_KEY` — Laravel `APP_KEY` (optional; Laravel can generate on startup but adding it avoids mid-run key regeneration).

How to get the Publish Profile (Portal)
1. Open the Azure Portal -> App Services -> select your Web App.
2. Overview -> Get publish profile -> click `Download`.
3. Open the downloaded `.PublishSettings` file and copy its contents to the GitHub secret `AZURE_WEBAPP_PUBLISH_PROFILE`.

Add secrets to GitHub
1. Go to your repository on GitHub -> Settings -> Secrets -> Actions -> New repository secret.
2. Create the secrets listed above. Use exact names used in `03_setup_ci.md`.

Where to reference them in the repo
- `.github/workflows/azure-deploy.yml` — referenced as `${{ secrets.AZURE_WEBAPP_PUBLISH_PROFILE }}` and DB secrets.
- `ytt/` scripts — for local testing you can export environment variables or use a `.env.local` (do NOT commit secrets).

Security notes
- Keep publish profile secret guarded — it grants deploy rights to the App Service.
- Prefer using Azure Managed Identity + GitHub OIDC for production-grade deployments where possible.
