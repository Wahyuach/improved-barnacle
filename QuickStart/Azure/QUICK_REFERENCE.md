# Azure Web App Deployment Quick Reference

## Essential Commands

### Create Resources
```bash
# Create resource group
az group create --name kantin-rg --location eastus

# Create App Service Plan (Linux)
az appservice plan create --name kantin-plan --resource-group kantin-rg --sku B1 --is-linux

# Create Web App
az webapp create --resource-group kantin-rg --plan kantin-plan --name kantin-app --runtime "PHP|8.2"

# Create MySQL Database
az mysql server create --resource-group kantin-rg --name kantin-db --admin-user dbadmin --admin-password P@ssw0rd123!
```

### Configure App Settings
```bash
az webapp config appsettings set --resource-group kantin-rg --name kantin-app --settings \
  APP_ENV=production \
  APP_DEBUG=false \
  APP_URL=https://kantin-app.azurewebsites.net \
  DB_HOST=kantin-db.mysql.database.azure.com \
  DB_PORT=3306 \
  DB_DATABASE=improved_barnacle \
  DB_USERNAME=dbadmin@kantin-db \
  DB_PASSWORD=P@ssw0rd123! \
  APP_KEY=base64:GENERATE_WITH_php_artisan_key:generate
```

### Deploy Application
```bash
# Setup Git deployment
az webapp deployment user set --user-name your-user
az webapp deployment source config-local-git --resource-group kantin-rg --name kantin-app

# Add remote
git remote add azure <git-clone-url>
git push azure main
```

### Monitor & Debug
```bash
# View logs in real-time
az webapp log tail --name kantin-app --resource-group kantin-rg

# Enable logging
az webapp log config --name kantin-app --resource-group kantin-rg --docker-container-logging filesystem

# Access Kudu console (browser)
https://kantin-app.scm.azurewebsites.net
```

## Critical Settings

### Required in App Settings
- `APP_KEY` - Generate: `php artisan key:generate --show`
- `DB_HOST` - Include `.mysql.database.azure.com` for Azure MySQL
- `DB_USERNAME` - Include `@servername` for Azure MySQL
- `DB_PASSWORD` - Keep secure, use Key Vault if possible
- `APP_URL` - Match your domain exactly

### Recommended Settings
- `APP_ENV=production`
- `APP_DEBUG=false`
- `LOG_CHANNEL=stack`
- `LOG_LEVEL=info`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`

## Files in Repository

| File | Purpose |
|------|---------|
| `startup.sh` | Runs on deployment - installs deps, migrations, assets |
| `web.config` | IIS config for Windows hosting (if used) |
| `.deployment` | Tells Azure to run startup.sh |
| `.env.example` | Template for environment variables |
| `QuickStart/Azure/README.md` | Overview and quick start |
| `QuickStart/Azure/DEPLOYMENT_GUIDE.md` | Detailed step-by-step guide |
| `QuickStart/Azure/CHECKLIST.md` | Pre-deployment verification |
| `.github/workflows/azure-deploy.yml` | Automated GitHub Actions deployment |

## Database Setup

### Step 1: Create Database
```bash
# Via Azure MySQL
az mysql server create --resource-group kantin-rg --name kantin-db --location eastus --sku-name B_Gen5_1 --admin-user dbadmin --admin-password P@ssw0rd123!

# Or use existing MySQL server
```

### Step 2: Create Application Database
```bash
# Connect to MySQL
mysql -h kantin-db.mysql.database.azure.com -u dbadmin@kantin-db -p

# Create database
CREATE DATABASE improved_barnacle;
```

### Step 3: Run Migrations
Automatic via `startup.sh` or manually:
```bash
# SSH into app (via Kudu Console at scm.azurewebsites.net)
php artisan migrate --force
php artisan db:seed
```

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Error 500 | Check logs: `az webapp log tail...` |
| DB won't connect | Verify credentials, firewall rules, hostname |
| Assets not loading | Run `npm run build`, check public/build exists |
| Composer fails | Clear cache: `rm -rf ~/.composer` |
| Permission denied | Check startup.sh has execute bit set |

## Performance Tips

- **Minimum Plan**: B1 (shared resources)
- **Recommended**: S1 (dedicated resources)
- **High Traffic**: S2 or S3, add Redis cache
- **Always enable**: "Always On" setting
- **Consider**: Azure CDN for static assets

## Security Checklist

- [ ] APP_KEY set to unique value
- [ ] APP_DEBUG=false in production
- [ ] HTTPS enabled (use managed SSL)
- [ ] Database password strong and secure
- [ ] Firewall allows only needed IPs
- [ ] Keep PHP and dependencies updated
- [ ] Review logs regularly
- [ ] Use Key Vault for secrets (optional)

## Support Resources

- **Azure Portal**: https://portal.azure.com
- **Azure CLI Docs**: https://learn.microsoft.com/cli/azure/
- **Laravel Docs**: https://laravel.com/docs
- **Laravel on Azure**: https://learn.microsoft.com/en-us/azure/developer/php/
- **App Service Logs**: `your-app.scm.azurewebsites.net`

## Quick Health Check

```bash
# After deployment, verify:
curl -I https://kantin-app.azurewebsites.net/
# Should return 200 OK

# Check in browser:
# 1. Home page loads
# 2. Can login
# 3. Dashboard displays
# 4. Database operations work (add product, edit, etc)
```

## SSH/Console Access

```bash
# Open SSH terminal (if enabled)
az webapp create-remote-connection --name kantin-app --resource-group kantin-rg

# Or use Kudu Console via browser
https://kantin-app.scm.azurewebsites.net/
```

## Rollback Previous Version

```bash
# List available backups
az webapp config backup list --resource-group kantin-rg --name kantin-app

# Restore from backup
az webapp config backup restore --resource-group kantin-rg --name kantin-app --backup-id <id>
```

---

**Last Updated**: November 15, 2025  
**For detailed info**: See QuickStart/Azure/README.md and QuickStart/Azure/DEPLOYMENT_GUIDE.md
