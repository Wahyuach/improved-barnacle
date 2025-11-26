01 — Create Azure resources
================================

Goal
- Create a Resource Group, MySQL Flexible Server (or Azure Database for MySQL), App Service Plan, and Web App (Linux).

Quick prerequisites
- Install Azure CLI and sign in: `az login`.
- Subscription selected: `az account set --subscription "<YOUR_SUBSCRIPTION_ID_OR_NAME>"`.

Example commands (update names and location):

```powershell
# variables
$rgName = "rb-barnacle-rg"
$location = "eastus"
$mysqlName = "rb-barnacle-mysql"
$serverSku = "GP_Standard_D4s_v3"
$adminUser = "rb_admin"
$adminPass = "<StrongPassword!123>"
$appName = "rb-barnacle-app-$(Get-Random -Minimum 1000 -Maximum 9999)"

# create resource group
az group create --name $rgName --location $location

# create MySQL flexible server (example: General Purpose)
az mysql flexible-server create --resource-group $rgName --name $mysqlName --admin-user $adminUser --admin-password $adminPass --sku-name Standard_B1ms --location $location

# create App Service plan (Linux, Code)
az appservice plan create --name "rb-barnacle-plan" --resource-group $rgName --is-linux --sku P1V2

# create Web App (Linux)
az webapp create --resource-group $rgName --plan "rb-barnacle-plan" --name $appName --runtime "PHP|8.2"

# (Optional) enable logging and configure settings
az webapp log config --name $appName --resource-group $rgName --web-server-logging filesystem

```

Where to update in the repo
- None — these commands are one-off. After creating resources, download the publish profile (next steps) and add secrets to GitHub (`04_secrets.md`).

Notes
- Replace the MySQL creation with your preferred sku/location. If you use Azure Database for MySQL (Single Server) use the respective CLI command.
- Ensure network access rules allow your App Service to connect to MySQL (private endpoints or public with firewall rules). See `QuickStart/Azure/` docs.
