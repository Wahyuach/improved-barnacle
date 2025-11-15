# Azure Web App Deployment Guide

This guide explains how to deploy the Kantin Kenanga inventory application to Azure Web App.

## Prerequisites

- Azure subscription
- Azure CLI or Azure Portal access
- Git installed locally
- MySQL database (Azure Database for MySQL or external)

## Deployment Steps

### 1. Create Azure Web App

```bash
# Create resource group
az group create --name kantin-rg --location eastus

# Create App Service Plan
az appservice plan create \
  --name kantin-plan \
  --resource-group kantin-rg \
  --sku B1 \
  --is-linux

# Create Web App
az webapp create \
  --resource-group kantin-rg \
  --plan kantin-plan \
  --name kantin-app \
  --runtime "PHP|8.2"
```

### 2. Configure MySQL Database

**Option A: Azure Database for MySQL**

```bash
# Create Azure MySQL server
az mysql server create \
  --resource-group kantin-rg \
  --name kantin-db \
  --location eastus \
  --admin-user dbadmin \
  --admin-password YourSecurePassword123! \
  --sku-name B_Gen5_1
```

**Option B: Use External MySQL Server**

If you have an existing MySQL server, ensure it's accessible from Azure.

### 3. Configure Application Settings

Set environment variables in Azure Portal or via CLI:

```bash
az webapp config appsettings set \
  --resource-group kantin-rg \
  --name kantin-app \
  --settings \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_KEY=base64:YOUR_GENERATED_KEY_HERE \
    APP_URL=https://kantin-app.azurewebsites.net \
    DB_HOST=your-mysql-host.mysql.database.azure.com \
    DB_PORT=3306 \
    DB_DATABASE=improved_barnacle \
    DB_USERNAME=dbadmin@kantin-db \
    DB_PASSWORD=YourSecurePassword123! \
    LOG_CHANNEL=stack \
    CACHE_STORE=database \
    QUEUE_CONNECTION=database \
    SESSION_DRIVER=database
```

### 4. Configure Web Server

The `web.config` file is included for IIS configuration if using Windows-based App Service.
For Linux-based App Service, the `startup.sh` script handles Nginx configuration.

### 5. Deploy Application

**Option A: Git Deployment**

```bash
# Configure Git deployment
az webapp deployment user set --user-name your-git-user --password your-git-password

# Get Git URL
az webapp deployment source config-local-git \
  --resource-group kantin-rg \
  --name kantin-app

# Add Azure remote to local Git repo
git remote add azure <git-clone-url-from-above>

# Push to Azure
git push azure main
```

**Option B: GitHub Actions**

1. Create GitHub Actions workflow in `.github/workflows/azure-deploy.yml`
2. Configure Azure credentials in GitHub Secrets
3. Push to trigger automatic deployment

**Option C: Azure DevOps**

Use Azure DevOps pipelines for CI/CD automation.

### 6. Initialize Database

After deployment, run migrations:

```bash
# Via SSH/Kudu Console (Azure Portal)
# Navigate to: <your-app>.scm.azurewebsites.net

# Or via Azure CLI
az webapp log deployment show --name kantin-app --resource-group kantin-rg
```

If migrations don't run automatically, SSH into the app and run:

```bash
php artisan migrate --force
php artisan db:seed
```

### 7. Configure SSL/TLS

```bash
# Add managed certificate
az webapp config ssl bind \
  --resource-group kantin-rg \
  --name kantin-app \
  --certificate-thumbprint <your-certificate-thumbprint>

# Or use Azure-managed certificates (automatic HTTPS)
```

### 8. Enable Logging

```bash
# Enable application logging
az webapp log config \
  --resource-group kantin-rg \
  --name kantin-app \
  --docker-container-logging filesystem

# View logs
az webapp log tail --resource-group kantin-rg --name kantin-app
```

## Environment Variables

Key environment variables for Azure deployment:

- `APP_ENV=production` - Set to production
- `APP_DEBUG=false` - Disable debug mode in production
- `APP_KEY` - Generate with: `php artisan key:generate`
- `DB_HOST` - Azure MySQL hostname (e.g., `server.mysql.database.azure.com`)
- `DB_USERNAME` - Format: `username@servername` for Azure MySQL
- `LOG_CHANNEL=stack` - Use stack logging for better error tracking

## Troubleshooting

### Application Shows Error 500

1. Check logs: `az webapp log tail --name kantin-app --resource-group kantin-rg`
2. Verify `storage` and `bootstrap/cache` permissions
3. Ensure `APP_KEY` is set correctly
4. Verify database credentials and connectivity

### Database Connection Fails

1. Check Azure MySQL firewall rules allow Web App's IP
2. Verify `DB_HOST` includes `.mysql.database.azure.com` suffix
3. Ensure `DB_USERNAME` includes `@servername` suffix for Azure MySQL
4. Test connection: `mysql -h host -u user -p`

### Composer/NPM Dependencies Not Installed

1. Clear Kudu cache: `rm -rf ~/.composer/cache`
2. Delete `vendor` folder and redeploy
3. Manually trigger deployment via Azure Portal

### Static Assets (CSS/JS) Not Loading

1. Ensure `npm run build` completed successfully
2. Check `public` folder has `build` directory with assets
3. Verify Nginx/IIS is serving static files correctly

## Performance Optimization

### For High Traffic

- Use Azure Cache for Redis for session/cache
- Enable Application Insights for monitoring
- Use Azure CDN for static assets
- Consider upgrading App Service Plan to S1 or higher

### Commands for Optimization

```bash
# Enable application insights
az webapp config appsettings set \
  --resource-group kantin-rg \
  --name kantin-app \
  --settings APPINSIGHTS_INSTRUMENTATIONKEY=<key>

# Scale up App Service Plan
az appservice plan update \
  --name kantin-plan \
  --resource-group kantin-rg \
  --sku S1
```

## Backup & Recovery

```bash
# Create application backup
az webapp config backup create \
  --resource-group kantin-rg \
  --name kantin-app

# List backups
az webapp config backup list \
  --resource-group kantin-rg \
  --name kantin-app

# Restore from backup
az webapp config backup restore \
  --resource-group kantin-rg \
  --name kantin-app \
  --backup-id <backup-id>
```

## Support

For issues with Azure deployment, check:
- Azure Portal > App Service > Deployment Center
- Azure Portal > App Service > Log Stream
- GitHub Actions logs if using automated deployment
