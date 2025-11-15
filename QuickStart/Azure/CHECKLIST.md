# Azure Deployment Checklist

Complete this checklist before deploying to Azure production.

## Pre-Deployment

- [ ] All tests passing: `php artisan test`
- [ ] No PHP errors: `php artisan tinker` (test basic functionality)
- [ ] Environment variables reviewed and configured
- [ ] Database backups created (if migrating from existing)
- [ ] SSL certificate obtained or using Azure managed certificate
- [ ] Domain name configured and pointing to Azure Web App
- [ ] CORS and CSRF configuration verified for production domain
- [ ] Security headers configured (if needed)

## Azure Infrastructure

- [ ] Resource Group created
- [ ] App Service Plan created (minimum B1 for production)
- [ ] Web App created with PHP 8.2 runtime
- [ ] MySQL database created (Azure Database for MySQL or external)
- [ ] Database credentials configured in Key Vault (optional but recommended)
- [ ] Storage account created for file uploads (if needed)
- [ ] Application Insights enabled for monitoring
- [ ] Alerts configured for critical errors

## Application Configuration

- [ ] `.env` file configured with production values:
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_KEY` generated
  - [ ] `APP_URL` set to production domain
  - [ ] `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` set
  - [ ] Email configuration (MAIL_* variables)
  
- [ ] Sensitive values stored in Azure Key Vault:
  - [ ] Database passwords
  - [ ] API keys
  - [ ] OAuth secrets

- [ ] Application settings in Azure Portal:
  - [ ] All required environment variables set
  - [ ] PHP version: 8.2
  - [ ] Always On: enabled (for continuous availability)
  - [ ] HTTP version: 2.0

## Files & Dependencies

- [ ] `startup.sh` is executable and in repository root
- [ ] `web.config` configured for IIS (if using Windows hosting)
- [ ] `.htaccess` in place for Apache (backup rewrite rules)
- [ ] `composer.json` and `package.json` up to date
- [ ] All dependencies in `composer.lock` and `package-lock.json`
- [ ] `node_modules` NOT in Git repository (included in `.gitignore`)
- [ ] Build artifacts not in Git (included in `.gitignore`)

## Database & Migrations

- [ ] Database created on Azure MySQL
- [ ] Connection to database tested from App Service
- [ ] Migrations reviewed and tested locally:
  - [ ] `php artisan migrate --force` ready to run
  - [ ] Rollback plan in place
- [ ] Seeders ready (if needed for initial data):
  - [ ] `php artisan db:seed` reviewed
- [ ] Database user has minimal required permissions
- [ ] Backup strategy configured in Azure

## Security

- [ ] HTTPS/SSL enforced (redirect HTTP to HTTPS)
- [ ] APP_KEY set to unique production value
- [ ] Debug mode disabled in production
- [ ] Sensitive routes protected with authentication
- [ ] CORS configured for allowed domains
- [ ] CSRF token validation enabled
- [ ] Rate limiting configured (if needed)
- [ ] Firewall rules configured (Azure MySQL)
- [ ] Application secrets NOT in code (using .env)
- [ ] Fortify/authentication properly configured

## Logging & Monitoring

- [ ] Log channel configured for production (stack channel)
- [ ] Log level set to `info` (not `debug`)
- [ ] Azure Application Insights integrated
- [ ] Log streaming enabled: `az webapp log config --web-server-logging filesystem`
- [ ] Monitoring alerts configured for:
  - [ ] HTTP 5xx errors
  - [ ] Database connection failures
  - [ ] High memory usage
- [ ] Log retention policy configured

## Performance

- [ ] Cache store set to appropriate driver (`database` or Redis)
- [ ] Session driver configured (database is default)
- [ ] Queue driver configured (using database)
- [ ] Static assets (CSS/JS) cached with far-future expires headers
- [ ] Gzip compression enabled in Nginx/IIS
- [ ] CDN configured for static assets (optional)
- [ ] Database indexes created on frequently queried columns

## DNS & Domain

- [ ] Domain registered and accessible
- [ ] DNS records point to Azure Web App:
  - [ ] A record or CNAME for primary domain
  - [ ] Alternate domains configured if needed
- [ ] SSL certificate installed or Azure managed cert enabled
- [ ] Certificate renewal configured if using custom cert
- [ ] Email domain verified (for sending emails)

## Testing

- [ ] User registration and login tested
- [ ] Product add/edit form tested
- [ ] Database operations tested (create, read, update, delete)
- [ ] File uploads tested (if applicable)
- [ ] Email notifications tested (if applicable)
- [ ] Mobile responsiveness verified
- [ ] Browser compatibility tested (Chrome, Firefox, Safari, Edge)
- [ ] Load testing performed (for expected traffic)

## Deployment Process

- [ ] Git repository updated with all changes
- [ ] Commit messages clear and descriptive
- [ ] Feature branches merged to main
- [ ] CI/CD pipeline configured (GitHub Actions or Azure DevOps)
- [ ] Deployment workflow tested in staging environment
- [ ] Backup created before first deployment
- [ ] Team informed of deployment schedule
- [ ] Rollback plan documented

## Post-Deployment

- [ ] Application loads without errors
- [ ] Database migrations executed successfully
- [ ] User can login and perform basic operations
- [ ] Admin dashboard accessible and functional
- [ ] Logs monitored for errors
- [ ] Performance metrics reviewed
- [ ] Uptime monitoring confirmed
- [ ] Team notified of successful deployment
- [ ] Deployment documented with date and version

## Ongoing Maintenance

- [ ] Daily log review for errors
- [ ] Weekly backup verification
- [ ] Monthly security updates for PHP and dependencies
- [ ] Quarterly performance review
- [ ] Annual security audit
- [ ] Database maintenance (optimize tables, update statistics)
- [ ] SSL certificate renewal reminders set

---

**Deployment Date:** _______________
**Deployed By:** _______________
**Notes:** _______________________________________________
