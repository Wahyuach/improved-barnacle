# 🚀 Installation & Deployment Guide

## Prerequisites

Sebelum memulai, pastikan sistem Anda memiliki:

- **PHP** 8.2 atau lebih tinggi
- **Node.js** 16+ dan npm
- **Composer** (latest)
- **Git**
- **Database**: SQLite (included) atau MySQL/PostgreSQL
- **Memory**: Minimal 1GB RAM

### Check Prerequisites

```bash
# Check PHP version
php --version

# Check Node.js
node --version
npm --version

# Check Composer
composer --version

# Check Git
git --version
```

---

## 📥 Installation Steps

### Step 1: Clone Repository

```bash
# Clone from GitHub
git clone https://github.com/Wahyuach/improved-barnacle.git

# Navigate to project
cd improved-barnacle

# List files to verify
ls -la
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

**Expected Output:**
```
✓ composer.json and composer.lock updated
✓ Installed 100+ packages
✓ npm packages installed successfully
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

**Verify in .env:**
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:xxx...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# SQLite is default - no extra config needed
```

### Step 4: Database Setup

```bash
# Create database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed with sample data
php artisan db:seed
```

**Expected Output:**
```
✓ Migration: 2025_11_13_000001_create_inventory_tables ... DONE
✓ Seeding database ...
✓ Database seeded successfully
```

### Step 5: Build Frontend Assets

```bash
# For development
npm run dev

# OR for production
npm run build
```

**Expected Output:**
```
✓ Built in 2.34 seconds
✓ dist/manifest.json created
✓ CSS and JS compiled successfully
```

### Step 6: Start Application

```bash
# Terminal 1 - PHP Server
php artisan serve

# Terminal 2 - Vite Dev Server (if using npm run dev)
npm run dev
```

**Expected Output:**
```
✓ Local: http://127.0.0.1:8000
✓ Vite server ready in 150ms
```

---

## ✅ Verification

Setelah instalasi, verifikasi semuanya berfungsi:

### 1. Browser Check
```
http://localhost:8000  → Should show welcome page
```

### 2. Database Check
```bash
php artisan tinker
> DB::table('products')->count()
# Should return: 8 (sample products)

> DB::table('categories')->count()
# Should return: 5 (sample categories)
```

### 3. Login Check
```
Email: admin@kantin.com
Password: password
```

### 4. Inventory Check
```
http://localhost:8000/inventory
# Should display inventory dashboard with products
```

---

## 🌐 Environment Configuration

### For Development

```env
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug

DB_CONNECTION=sqlite
```

### For Production

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kantin_inventaris
DB_USERNAME=user
DB_PASSWORD=password

CACHE_DRIVER=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

---

## 🗄️ Database Configuration

### Using SQLite (Default)
```env
DB_CONNECTION=sqlite
# File: database/database.sqlite
```

### Using MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kantin_inventaris
DB_USERNAME=root
DB_PASSWORD=
```

**Setup MySQL Database:**
```sql
CREATE DATABASE kantin_inventaris;
CREATE USER 'kantin'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON kantin_inventaris.* TO 'kantin'@'localhost';
FLUSH PRIVILEGES;
```

### Using PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kantin_inventaris
DB_USERNAME=postgres
DB_PASSWORD=
```

---

## 📝 Configuration Files

### Key Files to Configure

**1. .env**
```env
APP_NAME="Kantin Kenanga"
APP_DEBUG=true
APP_URL=http://localhost:8000
```

**2. config/app.php**
```php
'name' => env('APP_NAME', 'Laravel'),
'timezone' => 'Asia/Jakarta',
'locale' => 'id',
```

**3. config/database.php**
Already configured - no changes needed for SQLite

---

## 🚀 Deployment to Production

### Step 1: Prepare Server
```bash
# SSH into server
ssh user@your-domain.com

# Update system
sudo apt update && sudo apt upgrade -y

# Install requirements
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-common \
    php8.2-mysql php8.2-sqlite3 php8.2-zip php8.2-gd php8.2-mbstring \
    composer nodejs npm git -y
```

### Step 2: Deploy Code
```bash
# Clone repository
git clone https://github.com/Wahyuach/improved-barnacle.git /var/www/kantin

# Navigate to project
cd /var/www/kantin

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### Step 3: Setup Environment
```bash
# Copy env file
cp .env.example .env

# Generate key
php artisan key:generate

# Update .env for production
nano .env
```

Set these in .env:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=kantin_inventaris
DB_USERNAME=kantin_user
DB_PASSWORD=strong_password_here

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### Step 4: Setup Database
```bash
# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force
```

### Step 5: Setup Web Server (Nginx)

Create `/etc/nginx/sites-available/kantin`:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/kantin/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    client_max_body_size 100M;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/kantin /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Step 6: Setup SSL (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d your-domain.com
```

### Step 7: Setup File Permissions
```bash
cd /var/www/kantin

# Set ownership
sudo chown -R www-data:www-data .

# Set permissions
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# Make storage writable
sudo chmod -R 777 storage logs
```

### Step 8: Setup Process Manager (Supervisor)

Create `/etc/supervisor/conf.d/kantin.conf`:
```ini
[program:kantin-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/kantin/artisan queue:work
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/kantin/storage/logs/queue.log
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start kantin-queue:*
```

### Step 9: Setup Cron Job

Add to crontab:
```bash
sudo crontab -e

# Add this line:
* * * * * cd /var/www/kantin && php artisan schedule:run >> /dev/null 2>&1
```

### Step 10: Optimize for Production
```bash
cd /var/www/kantin

# Optimize
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup backups (optional)
php artisan schedule:run
```

---

## 📋 Checklist Setelah Instalasi

- [ ] PHP version checked (8.2+)
- [ ] Composer dependencies installed
- [ ] NPM packages installed
- [ ] .env file configured
- [ ] APP_KEY generated
- [ ] Database migrations ran
- [ ] Sample data seeded
- [ ] Assets built (npm run build)
- [ ] Can login with admin@kantin.com
- [ ] Can access /inventory
- [ ] Products displaying correctly
- [ ] Can add/edit/delete products
- [ ] Stock movements working
- [ ] All documentation read

---

## 🆘 Troubleshooting

### Installation Issues

#### "composer install" fails
```bash
# Clear composer cache
composer clear-cache

# Try again
composer install
```

#### "npm install" fails
```bash
# Clear npm cache
npm cache clean --force

# Install again
npm install
```

#### "php artisan migrate" fails
```bash
# Check database connection
php artisan db:show

# If SQLite:
touch database/database.sqlite
php artisan migrate
```

### Runtime Issues

#### "Assets not loading"
```bash
php artisan cache:clear
npm run build
```

#### "Page shows blank"
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear config cache
php artisan config:clear
```

#### "500 Internal Server Error"
```bash
# Check permissions
chmod -R 775 storage bootstrap/cache

# Check logs for details
tail -f storage/logs/laravel.log
```

#### "Database connection error"
```bash
# Test connection
php artisan db:show

# Check .env file
cat .env | grep DB_

# Verify database file exists (SQLite)
ls -la database/database.sqlite
```

---

## 🔧 Useful Commands

### Development
```bash
# Clear all caches
php artisan cache:clear && php artisan config:clear

# Reset database
php artisan migrate:refresh --seed

# Create new user
php artisan tinker
> User::factory()->create(['email' => 'user@test.com'])

# Tail logs
tail -f storage/logs/laravel.log
```

### Production
```bash
# Optimize
php artisan optimize

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Backup database
php artisan db:backup

# Run queue
php artisan queue:work
```

---

## 📊 System Requirements Summary

| Item | Minimum | Recommended |
|------|---------|-------------|
| PHP | 8.2 | 8.3+ |
| MySQL | 5.7 | 8.0+ |
| Memory | 512MB | 2GB+ |
| Disk | 500MB | 5GB+ |
| Node.js | 16 | 18+ |

---

## 🎉 Success!

Setelah menyelesaikan instalasi:

1. ✅ Buka browser ke `http://localhost:8000`
2. ✅ Login dengan `admin@kantin.com` / `password`
3. ✅ Akses inventory di `/inventory`
4. ✅ Baca dokumentasi di `PANDUAN_PENGGUNAAN.md`
5. ✅ Mulai gunakan sistem!

---

**Happy Deploying! 🚀**

*For more help, refer to: PANDUAN_PENGGUNAAN.md or ARCHITECTURE.md*

*Last Updated: November 13, 2025*
