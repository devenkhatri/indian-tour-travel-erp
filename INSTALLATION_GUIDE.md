# BharatTravel ERP & CRM - Complete Installation Guide

This guide walks you through setting up and running the BharatTravel Holidays ERP platform locally or on a dedicated development environment.

---

## 📋 System Prerequisites

Ensure your system has the following installed:

1. **PHP:** Version 8.2 or higher (**PHP 8.4** recommended).
   - *Required PHP Extensions:* `pdo_mysql`, `curl`, `mbstring`, `openssl`, `bcmath`, `xml`, `gd`, `zip`.
2. **Composer:** Version 2.2 or higher.
3. **Database Server:** MySQL 8.0+ or MariaDB 10.4+.
4. **Node.js & NPM:** Node.js 18.x or 20.x LTS with NPM.

---

## 🚀 Step-by-Step Installation

### Step 1: Clone the Repository
```bash
git clone <repository_url> travelcrm
cd travelcrm
```

### Step 2: Install PHP Dependencies
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
```

### Step 3: Install & Compile Frontend Assets
```bash
npm install
npm run build
```

### Step 4: Configure the Environment File
Copy the example environment file and generate a secure application encryption key:
```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` in your editor and configure your database parameters:
```ini
APP_NAME="BharatTravel ERP"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travelcrm
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Create Database and Run Migrations
Create the MySQL database if it does not already exist:
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS travelcrm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Run all database migrations and populate realistic Indian travel marketplace seed data:
```bash
php artisan migrate:fresh --seed
```

### Step 6: Create Storage Symlink
Link the storage public disk so uploaded package images and vouchers are publicly accessible:
```bash
php artisan storage:link
```

### Step 7: Start Development Server
```bash
php artisan serve
```

The application is now live at **`http://127.0.0.1:8000`**!

---

## 🔐 Default Demo Logins

| Portal | Email | Password | Role Description |
|---|---|---|---|
| **Admin CRM / ERP** | `admin@bharattravel.com` | `password` | Super Admin (Full Access) |
| **Admin CRM / ERP** | `sales@bharattravel.com` | `password` | Staff Consultant (Sales Desk) |
| **B2B Agent Portal** | `agent@shreetravels.com` | `password` | B2B Wholesale Agent |
| **Customer Portal** | `rahul.sharma@gmail.com` | `password` | Retail B2C Traveller |

> 💡 **Tip:** The `/login` page features 1-click quick login buttons to switch roles without typing credentials.

---

## 🧪 Verification & Health Check

Run the automated test suite to verify your installation:
```bash
php artisan test --compact
```
Expected output:
```
Tests:    33 passed (95 assertions)
Duration: ~1.2s
```

