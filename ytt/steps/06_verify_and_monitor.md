06 — Verify deployment and monitor
===================================

Steps after a successful deploy

1. Visit the app URL
   - `https://<your-app-name>.azurewebsites.net`
   - Confirm the homepage loads and critical pages function.

2. Check logs (streaming)
```powershell
az webapp log tail --name <appName> --resource-group <rgName>
```

3. Check App Service diagnostics in the Portal
   - Diagnose and solve problems -> Availability and Performance -> Live HTTP Traffic.

4. Smoke test endpoint (example)
```powershell
Invoke-WebRequest -UseBasicParsing "https://<your-app-name>.azurewebsites.net/health" -TimeoutSec 10
```

5. If migrations failed, troubleshoot locally and re-run migrations manually with the right DB creds.

6. Application Insights or Logs
   - If you enabled Application Insights, check traces and failures there.

7. Rollback plan
   - Keep a backup of DB and files before running migrations in production.
   - If deploy broke the site, redeploy the previous commit or swap to a staging slot.

Where to look in the repo
- `startup.sh` — logs about startup steps will show here.
- `.github/workflows/azure-deploy.yml` — CI logs available in Actions for build steps.
