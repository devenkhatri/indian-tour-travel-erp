# BharatTravel ERP & CRM - Production Deployment Guide

This guide details best practices for deploying BharatTravel ERP to a production Ubuntu/Debian server using Nginx, PHP 8.4-FPM, MySQL 8, Supervisor, and SSL certificates.

---

## 🏗️ Production Server Architecture

```
Internet / HTTPS (Port 443)
       │
       ▼
  [Cloudflare / Nginx Reverse Proxy with TLS]
       │
       ▼
  [PHP 8.4-FPM Socket / Unix Domain Socket]
       │
       ▼
  [Laravel Application: /var/www/travelcrm]
       │
       ├──► MySQL 8.0 (Port 3306)
       ├──► Redis (Cache & Session)
       └──► Supervisor (Queue Workers & Scheduler)
```

---

## 🛠️ Step 1: Server Provisioning

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Add Ondřej Surý PHP PPA for PHP 8.4
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.4 and required extensions
sudo apt install -y php8.4-fpm php8.4-mysql php8.4-mbstring php8.4-xml \
                    php8.4-curl php8.4-zip php8.4-bcmath php8.4-gd php8.4-intl \
                    php8.4-redis nginx mysql-server supervisor certbot python3-certbot-nginx
```

---

## 📁 Step 2: Application Code Deployment

```bash
# Clone repository to web root
sudo mkdir -p /var/www/travelcrm
sudo chown -R $USER:www-data /var/www/travelcrm
cd /var/www/travelcrm

git clone <repo-url> .

# Install production dependencies
composer install --no-dev --optimize-autoloader --no-interaction
npm install
npm run build

# Setup production environment
cp .env.example .env
php artisan key:generate
```

Configure `.env` for production:
```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bharattravel.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=travelcrm_prod
DB_USERNAME=travelcrm_user
DB_PASSWORD=SecurePasswordHere!

SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

Run migrations and storage link:
```bash
php artisan migrate --force
php artisan storage:link

# Cache framework configs for maximum speed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set file permissions:
```bash
sudo chown -R www-data:www-data /var/www/travelcrm/storage /var/www/travelcrm/bootstrap/cache
sudo chmod -R 775 /var/www/travelcrm/storage /var/www/travelcrm/bootstrap/cache
```

---

## 🌐 Step 3: Nginx Virtual Host Configuration

Create `/etc/nginx/sites-available/bharattravel.conf`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name bharattravel.com www.bharattravel.com;
    root /var/www/travelcrm/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site and provision SSL via Let's Encrypt:
```bash
sudo ln -s /etc/nginx/sites-available/bharattravel.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Install free SSL certificate
sudo certbot --nginx -d bharattravel.com -d www.bharattravel.com
```

---

## ⚙️ Step 4: Background Workers & Cron Scheduling

### 1. Cron Scheduler
Add the Laravel schedule runner to cron:
```bash
crontab -e -u www-data
```
Add line:
```cron
* * * * * cd /var/www/travelcrm && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Supervisor Queue Worker
Create `/etc/supervisor/conf.d/travelcrm-worker.conf`:
```ini
[program:travelcrm-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/travelcrm/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/travelcrm/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start travelcrm-worker:*
```

