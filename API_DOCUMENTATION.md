# BharatTravel ERP & CRM - API & Endpoint Documentation

This document specifies the endpoints, request/response formats, authentication, and error codes for the BharatTravel platform.

---

## 🔒 Authentication & Security

- **Web Sessions:** Cookie-based CSRF-protected session authentication using Laravel's standard session driver.
- **CSRF Token:** All state-changing requests (`POST`, `PUT`, `DELETE`) require the `X-CSRF-TOKEN` header or `_token` form field.
- **Role Enforcement:** Handled via middleware:
  - `auth`: Requires authenticated session.
  - `admin`: Requires `role` in `['super_admin', 'admin', 'staff']`.
  - `agent`: Requires `role == 'agent'` and an approved agent profile.
  - `customer`: Requires `role == 'customer'`.

---

## 🌐 Public Marketplace Endpoints

### 1. Newsletter Subscription
- **Method:** `POST`
- **URL:** `/newsletter/subscribe`
- **Headers:** `Content-Type: application/json`, `X-CSRF-TOKEN: <token>`
- **Request Payload:**
```json
{
  "email": "traveler@example.com"
}
```
- **Response `200 OK`:**
```json
{
  "success": true,
  "message": "You have successfully subscribed to BharatTravel secret deals & discounts!"
}
```

### 2. Contact & General Travel Inquiry
- **Method:** `POST`
- **URL:** `/contact`
- **Request Form Data:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| `name` | string | Yes | Prospect full name |
| `email` | string | Yes | Valid email |
| `phone` | string | Yes | Mobile number |
| `subject` | string | Yes | Inquiry subject |
| `message` | string | Yes | Travel requirements |
- **Response:** `302 Redirect` back with `session('success')`.

---

## 🧳 Customer Self-Service Endpoints

### 1. View Customer Bookings
- **Method:** `GET`
- **URL:** `/customer/bookings`
- **Headers:** Authenticated Customer Cookie

### 2. Download Travel Voucher PDF
- **Method:** `GET`
- **URL:** `/customer/bookings/{id}/voucher`
- **Response Headers:** `Content-Type: application/pdf`, `Content-Disposition: attachment; filename="Voucher-BKG-XXXX.pdf"`

### 3. Download GST Tax Invoice PDF
- **Method:** `GET`
- **URL:** `/customer/bookings/{id}/invoice`
- **Response Headers:** `Content-Type: application/pdf`, `Content-Disposition: attachment; filename="Invoice-INV-XXXX.pdf"`

### 4. Create Co-Traveller Passenger
- **Method:** `POST`
- **URL:** `/customer/passengers`
- **Request Form Data:**
```json
{
  "first_name": "Aarav",
  "last_name": "Sharma",
  "gender": "male",
  "date_of_birth": "1992-05-14",
  "passport_number": "Z1234567",
  "passport_expiry": "2032-05-14",
  "passport_issuing_country": "India"
}
```

### 5. Submit Support Ticket
- **Method:** `POST`
- **URL:** `/customer/support`
- **Request Form Data:** `subject`, `category`, `priority`, `message`

---

## 🤝 B2B Partner Agent Endpoints

### 1. Agent Package Tariffs
- **Method:** `GET`
- **URL:** `/agent/packages`
- **Response:** HTML View showing wholesale net rates and commission calculations.

### 2. Process Client Booking
- **Method:** `POST`
- **URL:** `/agent/bookings`
- **Request Form Data:**
```json
{
  "package_id": 1,
  "departure_date": "2026-12-15",
  "adults": 2,
  "children": 1,
  "client_name": "Rajesh Singhania",
  "client_phone": "9811223344",
  "client_email": "rajesh@client.com",
  "notes": "Vegetarian meals requested"
}
```
- **Business Action:** Automatically debits agent's wallet or credit line, issues confirmed booking, and credits agent commission to wallet.

---

## 🏢 Admin ERP & CRM Endpoints

### 1. Create Lead Inquiry
- **Method:** `POST`
- **URL:** `/admin/leads`
- **Request Payload:**
```json
{
  "name": "Vikram Seth",
  "email": "vikram@example.com",
  "phone": "9820011223",
  "destination": "Switzerland & France",
  "category": "holiday_package",
  "priority": "high",
  "status": "new",
  "adults": 2,
  "budget": 450000
}
```
- **Response:** `302 Redirect` to `/admin/leads/{id}`.

### 2. Convert Lead to Customer
- **Method:** `POST`
- **URL:** `/admin/leads/{id}/convert`
- **Response:** `302 Redirect` to newly created `/admin/customers/{customer_id}`.

### 3. Generate & Stream Quotation PDF
- **Method:** `GET`
- **URL:** `/admin/quotations/{id}/pdf`
- **Response:** `application/pdf` stream.

### 4. Export Bookings CSV
- **Method:** `GET`
- **URL:** `/admin/reports/bookings/csv`
- **Response Headers:** `Content-Type: text/csv`, `Content-Disposition: attachment; filename="BharatTravel_Bookings_YYYY-MM-DD.csv"`

