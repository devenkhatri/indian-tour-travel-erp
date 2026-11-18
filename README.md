# BharatTravel Holidays ERP & B2B/B2C Travel Marketplace

[![Laravel](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![Tests](https://img.shields.io/badge/Tests-33%20Passed-brightgreen.svg)]()
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A modern, full-stack **Indian Travel Marketplace & Travel Agency Enterprise Resource Planning (ERP / CRM)** web platform built on Laravel 13 and PHP 8.4. Designed specifically for Indian outbound and domestic tour operators, DMCs, corporate travel managers, and B2B partner agent networks.

---

## 🌟 Key Application Portals

### 1. Public Travel Marketplace (`/`)
- **Holiday Packages:** Filter by Domestic / International, price range, duration, themes (Adventure, Honeymoon, Family, Wildlife, Heritage, Pilgrimage).
- **Destinations Showcase:** Rich destination guides, best time to visit, weather advisories, popular activities, and local attraction maps.
- **Dedicated Service Desks:**
  - 🛂 **Visa Assistance** (Tourist, Business, Transit, Student, eVisa, Visa on Arrival).
  - 📘 **Passport Assistance** (Fresh passport, Tatkaal express, Renewal, Police Clearance Certificate).
  - ✈️ **Flight Reservations** (Domestic & International PNR ticketing with multi-class options).
  - 🏨 **Hotel Stays & Luxury Resorts** (5-star, Boutique, Heritage Havelis, Houseboats).
  - 🚆 **IRCTC Train Assistance** (Tatkaal assistance, Vande Bharat, Tourist circuits).
  - 🚌 **Luxury Volvo & Intercity Buses** (Sleeper, Semi-sleeper, AC Volvo).
  - 🗺️ **Customized Holiday Tour Planner** (Bespoke itinerary builder with pax counts and budget preferences).
- **Content Marketing:** SEO-rich travel blogs, curated customer reviews, interactive FAQ accordion, and newsletter broadcast subscriptions.

### 2. Customer Self-Service Portal (`/customer/*`)
- **Travel Dashboard:** Real-time summary of upcoming trips, total holiday spend, and active service files.
- **My Bookings:** Detailed booking cards with travel dates, passenger manifests, payment receipts, balance status.
- **1-Click PDF Downloads:** Instant download of **Travel Vouchers** with emergency helpline, and **GST Tax Invoices**.
- **Visa & Passport Tracker:** Real-time tracking of consular submission, biometric appointments, and dispatch status.
- **Saved Co-Travellers:** Store passport numbers, expiry dates, and DOB for lightning-fast future bookings.
- **Customer Wishlist:** Save favorite holiday destinations and packages.
- **Support Desk:** Raise and track priority support requests.

### 3. B2B Travel Agent Portal (`/agent/*`)
- **Agent Dashboard:** Real-time KPI feed showing wholesale sales volume, wallet balance, credit line utilization, and commission rates.
- **B2B Wholesale Package Tariffs:** Dynamic calculation displaying Gross Selling Price, Agent Net Price, Commission %, and instant Margin.
- **Client Booking Terminal:** Book holiday packages on behalf of clients with instant GST calculation and automated wallet commission credit.
- **Financial Ledger & Wallet:** Monitor deposits, NEFT top-up instructions, debit adjustments, and credit line limits.
- **Commission Statements:** Transparent ledger of earned, pending, and settled B2B commissions.

### 4. Admin Travel ERP & CRM (`/admin/*`)
- **Executive KPI Dashboard:** Real-time metrics for Gross Revenue, Realized Payments, Outstanding Balances, Conversion Rates, and Active Trips.
- **Lead Inquiries & CRM Pipeline:** Kanban/Table view of inquiries with priority badges, status workflows (New, Contacted, Interested, Quotation Sent, Converted, Lost), call & activity logs, and 1-click lead-to-customer conversion.
- **Customer 360° Management:** Comprehensive traveler profiles, past booking history, linked co-travelers, and financial ledger.
- **Package Management:** Rich package editor with day-by-day itineraries, flight & meal inclusions, exclusions, seasonal pricing, and departures.
- **Quotation Generator:** Dynamic quotation builder generating professional **PDF quotations** with terms, itinerary breakdown, and 1-click booking conversion.
- **Operations & Dispatch Desk:** Assign tour managers, local transport drivers, vehicle plate numbers, pickup times, and flight tickets.
- **Supplier & DMC Management:** Vendor database covering Hoteliers, DMCs, Transport Providers, and Visa Facilitators.
- **Accounts & GST Compliance:**
  - Automated GST calculation under **SAC 998555** (5% Tour Operator without ITC).
  - Tax Invoices with GSTIN, State Code, Place of Supply, and CGST/SGST/IGST breakdown.
  - Expense logging and financial profit margin analytics.
- **Team & Branch Management:** Organize staff across multiple city branches with sales targets and role-based permissions.
- **CMS Control Center:** Real-time management of Hero Banners, Customer Testimonials, Blog Articles, and FAQ categories.
- **Settings:** Configurable agency details, GSTIN, bank coordinates for RTGS/NEFT, and Razorpay payment keys.

---

## 🛠️ Technology Stack

| Layer | Technology | Details |
|---|---|---|
| **Backend Framework** | Laravel 13.42.0 | PHP 8.4.7 with strict type hinting, constructor promotion & Eloquent ORM |
| **Database** | MySQL 8.0 | Full schema with 30+ tables, foreign keys, indexes, and soft deletes |
| **Frontend Styling** | Tailwind CSS v4.0 | Fully responsive modern UI with luxury travel color palette |
| **Client Interactivity** | Alpine.js v3 | Dynamic modals, dropdowns, reactive tabs, and calculators |
| **Icons** | Lucide Icons & FontAwesome 6 | High-definition travel and business iconography |
| **PDF Engine** | Barryvdh DomPDF v3.1 | Pixel-perfect A4 Travel Vouchers, Tax Invoices & Quotations |
| **Testing** | PHPUnit 11 | 33 automated Feature tests verifying Auth, Portals, CRM & APIs |

---

## 🔑 Default Login Credentials

All accounts come pre-configured with realistic Indian travel agency demo data:

| Portal | Role | Email Address | Password |
|---|---|---|---|
| **Admin ERP** | Super Administrator | `admin@bharattravel.com` | `password` |
| **Admin ERP** | Sales Consultant | `sales@bharattravel.com` | `password` |
| **B2B Portal** | Partner Travel Agent | `agent@shreetravels.com` | `password` |
| **Customer Portal** | Registered Traveler | `rahul.sharma@gmail.com` | `password` |

> ⚡ **Quick Login:** The `/login` page features 1-click quick login buttons to instantly switch between Admin, Sales Staff, B2B Agent, and Customer roles.

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP >= 8.2 (PHP 8.4 recommended) with `pdo_mysql`, `curl`, `mbstring`, `gd` extensions enabled.
- Composer 2.x
- MySQL 8.0+
- Node.js 18+ & NPM

### Setup Instructions

```bash
# 1. Clone repository
git clone <repo-url> travelcrm
cd travelcrm

# 2. Install PHP and JS dependencies
composer install
npm install

# 3. Setup environment configuration
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env (DB_DATABASE=travelcrm, DB_USERNAME=root, DB_PASSWORD=)
# Ensure MySQL service is running and database exists:
mysql -u root -e "CREATE DATABASE IF NOT EXISTS travelcrm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Run migrations & database seeders
php artisan migrate:fresh --seed

# 6. Build frontend assets
npm run build

# 7. Start the development server
php artisan serve
```

Access the application in your browser at `http://localhost:8000`.

---

## 🧪 Automated Test Suite

Run the full automated test suite covering all critical workflows:

```bash
php artisan test --compact
```

```
Tests:    33 passed (95 assertions)
Duration: 1.17s
```

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).
