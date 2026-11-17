# BharatTravel ERP & CRM - Database Documentation

**Database Engine:** MySQL 8.0+  
**Character Set:** `utf8mb4`  
**Collation:** `utf8mb4_unicode_ci`  
**Total Tables:** 32  

---

## 🏗️ Entity Relationship Overview

The BharatTravel ERP database is organized into 9 logical domains:
1. **Core & RBAC:** `users`, `roles`, `permissions`, `role_user`, `permission_role`, `branches`, `departments`, `employees`.
2. **CRM & Prospects:** `customers`, `leads`, `lead_sources`, `lead_activities`.
3. **Catalog & Inventory:** `countries`, `destinations`, `package_categories`, `packages`, `package_itineraries`, `package_departures`, `package_prices`.
4. **Reservations & Travelers:** `bookings`, `booking_passengers`, `vouchers`, `wishlists`, `support_tickets`.
5. **Specialized Services:** `visa_requirements`, `visa_applications`, `passport_applications`, `flight_inquiries`, `hotel_inquiries`, `train_inquiries`, `bus_inquiries`.
6. **Quotations:** `quotations`, `quotation_items`.
7. **Accounts & Taxation:** `invoices`, `invoice_items`, `payments`, `expenses`, `settings`.
8. **Operations & Vendors:** `suppliers`, `operations_trips`, `trip_assignments`.
9. **B2B Network & Marketing:** `agents`, `agent_commissions`, `banners`, `testimonials`, `blogs`, `faqs`, `subscribers`.

---

## 📋 Comprehensive Table Specifications

### 1. Core & Access Control

#### `users`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `name` | VARCHAR(150) | No | Full name of user |
| `email` | VARCHAR(150) | No | Unique email address |
| `phone` | VARCHAR(25) | Yes | Mobile number |
| `password` | VARCHAR(255) | No | Bcrypt hashed password |
| `role` | VARCHAR(30) | No | Main role: `super_admin`, `admin`, `staff`, `agent`, `customer` |
| `status` | VARCHAR(20) | No | Status: `active`, `inactive`, `suspended` |
| `avatar` | VARCHAR(255) | Yes | Profile picture URL |
| `timestamps` | TIMESTAMP | Yes | Created and updated dates |

#### `branches`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `name` | VARCHAR(100) | No | Branch office title (e.g., Delhi CP, Mumbai BKC) |
| `code` | VARCHAR(20) | No | Branch code (e.g., DEL-HQ, BOM-BR) |
| `city` | VARCHAR(100) | No | City of operation |
| `address` | TEXT | Yes | Full physical address |
| `phone` | VARCHAR(50) | Yes | Branch contact telephone |
| `email` | VARCHAR(100) | Yes | Branch office email |
| `is_active` | BOOLEAN | No | Operational status |

#### `employees`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `user_id` | BIGINT UNSIGNED | No | FK referencing `users.id` |
| `branch_id` | BIGINT UNSIGNED | Yes | FK referencing `branches.id` |
| `department_id` | BIGINT UNSIGNED | Yes | FK referencing `departments.id` |
| `employee_code` | VARCHAR(50) | No | Unique employee ID (e.g., EMP-001) |
| `designation` | VARCHAR(100) | No | Position (e.g., Senior Holiday Consultant) |
| `sales_target` | DECIMAL(12,2) | No | Monthly sales quota in INR |
| `commission_rate` | DECIMAL(5,2) | No | Staff commission percentage |
| `joining_date` | DATE | Yes | Date of joining |
| `is_active` | BOOLEAN | No | Employment status |

---

### 2. CRM & Customer 360°

#### `customers`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `user_id` | BIGINT UNSIGNED | Yes | Optional link to `users.id` for self-service portal |
| `customer_code` | VARCHAR(50) | No | Unique code (e.g., CUST-0001) |
| `name` | VARCHAR(150) | No | Full legal name |
| `email` | VARCHAR(150) | Yes | Primary email |
| `phone` | VARCHAR(25) | No | Primary phone number |
| `alternate_phone` | VARCHAR(25) | Yes | Secondary contact |
| `type` | VARCHAR(20) | No | `b2c`, `corporate`, `vip` |
| `passport_number` | VARCHAR(50) | Yes | Primary passport number |
| `passport_expiry` | DATE | Yes | Expiry date of passport |
| `nationality` | VARCHAR(50) | Yes | Default: Indian |
| `city` | VARCHAR(100) | Yes | City of residence |
| `state` | VARCHAR(100) | Yes | State of residence |
| `gstin` | VARCHAR(30) | Yes | GSTIN for corporate B2B billing |

#### `leads`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `lead_number` | VARCHAR(50) | No | Unique identifier (e.g., LD-2026-001) |
| `name` | VARCHAR(150) | No | Prospect name |
| `phone` | VARCHAR(25) | No | Contact telephone / WhatsApp |
| `email` | VARCHAR(150) | Yes | Prospect email |
| `destination` | VARCHAR(150) | No | Target destination or holiday interest |
| `category` | VARCHAR(50) | Yes | e.g. holiday_package, visa, flight |
| `priority` | VARCHAR(20) | No | `low`, `medium`, `high`, `urgent` |
| `status` | VARCHAR(30) | No | `new`, `contacted`, `interested`, `follow_up`, `quotation_sent`, `negotiation`, `converted`, `lost` |
| `source` | VARCHAR(50) | Yes | `website`, `whatsapp`, `walk_in`, `referral`, `phone` |
| `assigned_to` | BIGINT UNSIGNED | Yes | FK referencing `users.id` (staff consultant) |
| `branch_id` | BIGINT UNSIGNED | Yes | FK referencing `branches.id` |
| `budget` | DECIMAL(12,2) | Yes | Estimated budget in INR |
| `adults` | INT | No | Number of adult passengers |
| `children` | INT | Yes | Number of children |
| `infants` | INT | Yes | Number of infants |
| `travel_start_date` | DATE | Yes | Anticipated departure date |
| `follow_up_date` | DATETIME | Yes | Scheduled follow-up reminder |
| `notes` | TEXT | Yes | Consultant remarks & client preferences |

---

### 3. Tour Packages & Itineraries

#### `packages`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `destination_id` | BIGINT UNSIGNED | No | FK referencing `destinations.id` |
| `package_code` | VARCHAR(50) | No | Unique code (e.g., PKG-KASH-01) |
| `title` | VARCHAR(200) | No | Package title |
| `slug` | VARCHAR(200) | No | Unique URL slug |
| `type` | VARCHAR(20) | No | `domestic` or `international` |
| `duration_days` | INT | No | Total tour days |
| `duration_nights` | INT | No | Total hotel stay nights |
| `starting_price` | DECIMAL(12,2) | No | Lead marketing price |
| `adult_price` | DECIMAL(12,2) | No | Standard twin-sharing adult rate |
| `child_price` | DECIMAL(12,2) | No | Child rate with bed |
| `infant_price` | DECIMAL(12,2) | No | Infant fee under 2 years |
| `departure_city` | VARCHAR(100) | Yes | Starting city |
| `highlights` | JSON | Yes | Bulleted selling points |
| `inclusions` | JSON | Yes | Tour inclusions |
| `exclusions` | JSON | Yes | Exclusions list |
| `is_featured` | BOOLEAN | No | Featured on marketplace homepage |
| `is_active` | BOOLEAN | No | Active visibility |

#### `package_itineraries`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `package_id` | BIGINT UNSIGNED | No | FK referencing `packages.id` |
| `day_number` | INT | No | Day number (1, 2, 3...) |
| `title` | VARCHAR(200) | No | Day header (e.g., Arrival in Srinagar) |
| `description` | TEXT | No | Detailed day schedule & sightseeing |
| `meals_info` | VARCHAR(100) | Yes | Meals provided (Breakfast, Lunch, Dinner) |
| `hotel_info` | VARCHAR(150) | Yes | Stay category / Hotel name |
| `activity_info` | VARCHAR(200) | Yes | Key highlight of the day |

---

### 4. Bookings, Billing & GST SAC 998555

#### `bookings`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `booking_number` | VARCHAR(50) | No | Unique reference (e.g., BKG-2026-0001) |
| `customer_id` | BIGINT UNSIGNED | No | FK referencing `customers.id` |
| `package_id` | BIGINT UNSIGNED | Yes | FK referencing `packages.id` |
| `agent_id` | BIGINT UNSIGNED | Yes | FK referencing `agents.id` (if B2B booking) |
| `service_type` | VARCHAR(30) | No | `package`, `flight`, `hotel`, `visa`, `passport`, `custom` |
| `departure_date` | DATE | Yes | Departure date |
| `return_date` | DATE | Yes | Return date |
| `adults` | INT | No | Adult count |
| `children` | INT | Yes | Children count |
| `infants` | INT | Yes | Infant count |
| `base_price` | DECIMAL(12,2) | No | Price before tax |
| `tax_amount` | DECIMAL(12,2) | No | 5% GST under SAC 998555 |
| `total_amount` | DECIMAL(12,2) | No | Final gross billing amount |
| `paid_amount` | DECIMAL(12,2) | No | Total collections received |
| `balance_amount` | DECIMAL(12,2) | No | Unpaid balance |
| `supplier_cost` | DECIMAL(12,2) | No | Direct vendor / supplier payable |
| `booking_status` | VARCHAR(30) | No | `confirmed`, `on_hold`, `completed`, `cancelled` |
| `payment_status` | VARCHAR(30) | No | `paid`, `partial`, `pending`, `refunded` |

#### `invoices`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `invoice_number` | VARCHAR(50) | No | Unique tax invoice number (e.g., INV-2026/001) |
| `booking_id` | BIGINT UNSIGNED | No | FK referencing `bookings.id` |
| `customer_id` | BIGINT UNSIGNED | No | FK referencing `customers.id` |
| `sac_code` | VARCHAR(20) | No | Standard **998555** (Tour Operator Services) |
| `taxable_amount` | DECIMAL(12,2) | No | Pre-tax base amount |
| `gst_rate` | DECIMAL(5,2) | No | Standard 5.00% without ITC |
| `cgst_amount` | DECIMAL(12,2) | No | 2.5% CGST (Intra-state) |
| `sgst_amount` | DECIMAL(12,2) | No | 2.5% SGST (Intra-state) |
| `igst_amount` | DECIMAL(12,2) | No | 5.0% IGST (Inter-state) |
| `total_amount` | DECIMAL(12,2) | No | Total invoice value including GST |
| `status` | VARCHAR(30) | No | `issued`, `paid`, `cancelled` |

---

### 5. B2B Agents & Financials

#### `agents`
| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key |
| `user_id` | BIGINT UNSIGNED | No | FK referencing `users.id` |
| `agency_name` | VARCHAR(150) | No | Registered trade name |
| `agency_code` | VARCHAR(50) | No | Unique agent code (e.g., AGT-001) |
| `owner_name` | VARCHAR(100) | No | Proprietor / Director name |
| `email` | VARCHAR(150) | No | Agency official email |
| `phone` | VARCHAR(25) | No | Agency hotline |
| `city` | VARCHAR(100) | No | City of operation |
| `state` | VARCHAR(100) | No | State (for GST PoS) |
| `credit_limit` | DECIMAL(12,2) | No | Approved credit line |
| `used_credit` | DECIMAL(12,2) | No | Currently utilized credit |
| `wallet_balance` | DECIMAL(12,2) | No | Available prepaid funds |
| `commission_rate` | DECIMAL(5,2) | No | Contracted commission (e.g. 5.00% to 10.00%) |
| `status` | VARCHAR(30) | No | `pending`, `approved`, `suspended` |

