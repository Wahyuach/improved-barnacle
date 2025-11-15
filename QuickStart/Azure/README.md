# Azure Web App Deployment Summary

Your Laravel application has been prepared for Azure Web App deployment. Here's what's been configured:

## Files Added/Modified

### 1. **startup.sh** (Updated)
- Comprehensive bash script for Nginx-based Linux App Service
- Automatically installs Composer dependencies
- Runs database migrations with `--force` flag
- Builds frontend assets with npm
- Sets proper file permissions for Laravel
- Configures Nginx to point to `/public` directory

### 2. **web.config** (New)
- IIS configuration for Windows-based App Service (if you switch to Windows hosting)
- URL rewriting rules to route all requests through `index.php`
- FastCGI handler configuration for PHP 8.2
- MIME type mappings for web fonts and JSON

### 3. **.deployment** (New)
- Azure deployment configuration file
- Specifies that `startup.sh` should run on deployment

### 4. **.env.example** (Updated)
- MySQL database configuration (changed from SQLite)
- Now uses `DB_CONNECTION=mysql` by default
- Includes MySQL host, database, username, password fields

### 5. **QuickStart/Azure/** (New Directory)
Location for all Azure deployment documentation:
- `START_HERE.md` - Quick overview and 3-minute quick start
- `README.md` - Overview of all files created
- `DEPLOYMENT_GUIDE.md` - Detailed step-by-step guide
- `CHECKLIST.md` - Pre & post-deployment verification
- `QUICK_REFERENCE.md` - Command reference and troubleshooting
- `SETUP_COMPLETE.md` - Implementation summary

### 6. **.github/workflows/azure-deploy.yml** (New)
- GitHub Actions workflow for automated deployment
- Builds application on every push to `main` branch
- Installs PHP 8.2, Composer dependencies, and npm packages
- Builds frontend assets
- Deploys to Azure Web App
- Includes health check after deployment

## Quick Start for Azure Deployment

### Step 1: Set Up Azure Web App

```bash
# Login to Azure
az login

# Create resource group
az group create --name kantin-rg --location eastus

# Create App Service Plan (Linux)
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

### Step 2: Create MySQL Database

**Azure Option:**
```bash
az mysql server create \
  --resource-group kantin-rg \
  --name kantin-db \
  --location eastus \
  --admin-user dbadmin \
  --admin-password YourSecurePassword123!
```

**Or use existing MySQL server** (update connection string)

### Step 3: Configure Application Settings

```bash
az webapp config appsettings set \
  --resource-group kantin-rg \
  --name kantin-app \
  --settings \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=https://kantin-app.azurewebsites.net \
    DB_HOST=your-mysql-host \
    DB_PORT=3306 \
    DB_DATABASE=improved_barnacle \
    DB_USERNAME=dbadmin \
    DB_PASSWORD=YourPassword123!
```

### Step 4: Generate APP_KEY

Generate a secure key locally:
```bash
php artisan key:generate --show
```

Copy the output and add it to Azure:
```bash
az webapp config appsettings set \
  --resource-group kantin-rg \
  --name kantin-app \
  --settings APP_KEY=base64:YOUR_KEY_HERE
```

### Step 5: Deploy Application

**Option A: Git Push**
```bash
# Set up deployment user
az webapp deployment user set --user-name your-git-user

# Get Git URL
az webapp deployment source config-local-git \
  --resource-group kantin-rg \
  --name kantin-app

# Add remote and push
git remote add azure <clone-url>
git push azure main
```

**Option B: GitHub Actions (Automated)**
1. Generate publish profile: Azure Portal > App Service > Deployment Center > Download profile
2. Add to GitHub Secrets as `AZURE_WEBAPP_PUBLISH_PROFILE`
3. Push to main branch - automatic deployment triggers

## Environment Variables Required

Set these in Azure Portal > App Service > Configuration > Application settings:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxxxxxxxxxx (generate with php artisan key:generate)
APP_URL=https://your-domain.azurewebsites.net
APP_NAME=Kantin Kenanga
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=your-mysql-host.mysql.database.azure.com
DB_PORT=3306
DB_DATABASE=improved_barnacle
DB_USERNAME=dbadmin@servername (important for Azure MySQL)
DB_PASSWORD=YourSecurePassword123!

LOG_CHANNEL=stack
LOG_LEVEL=info
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## Important Notes

### For Azure MySQL
- Username must include `@servername` suffix: `dbadmin@kantin-db`
- Firewall rules must allow Web App's outbound IP
- Hostname includes `.mysql.database.azure.com` suffix

### Performance
- B1 plan suitable for low-traffic apps
- S1 or higher recommended for production traffic
- Enable "Always On" for consistent performance

### Security
- Always use HTTPS (SSL certificate)
- Keep APP_KEY secret
- Never commit `.env` file to Git
- Use Azure Key Vault for sensitive secrets

### Scaling
- For high traffic, upgrade to S1, S2, or higher
- Consider Azure Cache for Redis
- Use Azure CDN for static assets

## Troubleshooting

### Application shows error 500
1. Check logs: `az webapp log tail --name kantin-app --resource-group kantin-rg`
2. Verify APP_KEY is set
3. Ensure database connection works
4. Check storage/bootstrap permissions

### Database won't connect
1. Verify DB credentials in App Settings
2. Check Azure MySQL firewall allows Web App
3. Ensure database exists: `improved_barnacle`
4. Test connection locally with same credentials

### Assets (CSS/JS) not loading
1. Ensure `npm run build` completed successfully
2. Check `public/build` directory exists
3. Verify Nginx is configured for static files

### Deployment fails
1. Check GitHub Actions logs (if using automation)
2. View App Service logs: Kudu Console or `az webapp log tail`
3. Verify `startup.sh` has execute permissions
4. Check all environment variables are set

## Next Steps

1. Read **QuickStart/Azure/START_HERE.md** for quick overview
2. Review **QuickStart/Azure/DEPLOYMENT_GUIDE.md** for detailed instructions
3. Complete **QuickStart/Azure/CHECKLIST.md** before going live
4. Configure GitHub Actions if using automated deployment
5. Set up monitoring and alerts in Azure
6. Test application thoroughly before production traffic

## File Structure

```
improved-barnacle/
├── startup.sh                    # Deployment startup script
├── web.config                    # IIS configuration
├── .deployment                   # Azure deployment config
├── .env.example                  # Updated with MySQL config
├── .github/workflows/
│   └── azure-deploy.yml          # GitHub Actions workflow
├── QuickStart/
│   ├── Azure/                    # Azure deployment documentation
│   │   ├── START_HERE.md
│   │   ├── README.md
│   │   ├── DEPLOYMENT_GUIDE.md
│   │   ├── CHECKLIST.md
│   │   ├── QUICK_REFERENCE.md
│   │   └── SETUP_COMPLETE.md
│   └── [other documentation]
├── app/
├── public/
├── resources/
├── bootstrap/
├── storage/
├── vendor/
└── ...
```

Your application is now ready for Azure Web App deployment! 🚀
