# BharatTravel ERP & CRM - Module Architecture & Functional Documentation

This document describes the internal architecture, functional controllers, models, views, and business workflows implemented in BharatTravel ERP.

---

## 🧭 System Architecture & Routing Map

The application is structured into four distinct functional pillars:

```
                  ┌─────────────────────────────────────┐
                  │      BharatTravel Platform          │
                  └──────────────────┬──────────────────┘
                                     │
    ┌────────────────┬───────────────┴───────────────┬────────────────┐
    │                │                               │                │
┌───▼────┐      ┌────▼───────┐                 ┌─────▼─────┐     ┌────▼───────┐
│ Public │      │  Customer  │                 │    B2B    │     │ Admin CRM  │
│ Portal │      │  Portal    │                 │   Agent   │     │   & ERP    │
│  (/)   │      │(/customer) │                 │  (/agent) │     │  (/admin)  │
└────────┘      └────────────┘                 └───────────┘     └────────────┘
```

---

## 📦 Module Breakdown

### 1. CRM Lead & Customer Pipeline
- **Primary Controller:** `App\Http\Controllers\Admin\LeadController` & `CustomerController`
- **Models:** `Lead`, `LeadActivity`, `LeadSource`, `Customer`, `User`
- **Key Views:**
  - `admin/leads/index.blade.php`: Searchable table with status filtering and quick action buttons.
  - `admin/leads/create.blade.php`: Comprehensive lead intake form with source, budget, and destination tags.
  - `admin/leads/show.blade.php`: Lead 360° cockpit featuring activity timeline, follow-up scheduler, call log notes, and 1-click customer conversion.
  - `admin/customers/index.blade.php`, `show.blade.php`: Traveler repository with travel history, passport info, and linked family members.
- **Business Workflows:**
  1. Inbound inquiries from public portal contact forms or direct staff entries are created with a unique `lead_number` (e.g., `LEAD-2026-1042`).
  2. Sales consultants log calls, meetings, and WhatsApp chats which append to the `lead_activities` timeline.
  3. Clicking **"Convert to Customer"** automatically queries existing customers by email/phone. If not found, a new customer record (`customer_code: CUST-xxxx`) is created and linked without data duplication.

---

### 2. Tour Packages & Itineraries
- **Primary Controller:** `App\Http\Controllers\Admin\PackageManagementController` & `PackageController`
- **Models:** `TourPackage`, `PackageItinerary`, `PackageDeparture`, `Destination`, `PackageCategory`
- **Key Views:**
  - `pages/packages/index.blade.php`: Public catalog with pricing, duration, and destination filters.
  - `pages/packages/show.blade.php`: Public package details with day-by-day tabs, hotel stays, inclusions/exclusions, and booking CTA.
  - `admin/packages/index.blade.php`, `create.blade.php`, `show.blade.php`, `edit.blade.php`: Admin CRUD suite with day-wise itinerary builder and departure date scheduler.
- **Business Rules:**
  - Multi-tier pricing: `starting_price`, `adult_price`, `child_price`, `infant_price`.
  - Dynamic day-by-day itineraries storing morning/evening activity highlights, meal plans (`CP/MAP/AP`), and accommodation names.

---

### 3. Quotation Generator & PDF Proposals
- **Primary Controller:** `App\Http\Controllers\Admin\QuotationController`
- **Models:** `Quotation`, `Customer`, `User`, `TourPackage`
- **Key Views:**
  - `admin/quotations/index.blade.php`: Quotations tracker with validity dates and statuses.
  - `admin/quotations/create.blade.php`: Dynamic quote creator calculating adult/child totals, hotel stay, flight inclusion, and GST.
  - `admin/quotations/show.blade.php`: Management overview with action to instantly convert into a confirmed booking.
  - `pdf/quotation.blade.php`: A4 proposal PDF featuring agency letterhead, custom greeting, price quotation, and cancellation policy.
- **Conversion Flow:**
  - Converting a quotation creates a `Booking` entry, generates an initial pending invoice, and changes the quotation status to `converted`.

---

### 4. Booking Lifecycle & Vouchers
- **Primary Controller:** `App\Http\Controllers\Admin\BookingManagementController` & `CustomerDashboardController`
- **Models:** `Booking`, `BookingPassenger`, `Voucher`, `Invoice`, `Payment`
- **Key Views:**
  - `admin/bookings/index.blade.php`, `show.blade.php`: Operations booking management.
  - `customer/bookings/index.blade.php`, `show.blade.php`: Customer self-service views.
  - `pdf/voucher.blade.php`: Official Travel Service Voucher with QR reference, pickup time, driver telephone, hotel vouchers, and 24/7 hotline.
  - `pdf/invoice.blade.php`: GST Tax Invoice with SAC 998555 breakdown, CGST/SGST/IGST, and digital seal.

---

### 5. B2B Travel Agent Network
- **Primary Controller:** `App\Http\Controllers\B2B\AgentDashboardController` & `AgentBookingController`
- **Models:** `Agent`, `AgentCommission`, `Payment`, `Booking`, `User`
- **Key Views:**
  - `agent/dashboard.blade.php`: Partner overview with live balance, credit line, and commission rate.
  - `agent/packages/index.blade.php`: B2B tariff list showing wholesale net prices and profit margin calculation.
  - `agent/bookings/create.blade.php`: B2B client checkout terminal with automated commission crediting.
  - `agent/wallet.blade.php`: Account ledger showing NEFT deposits and debit history.
  - `agent/commissions.blade.php`: Statement of earned and settled agency commissions.
- **Commission & Financial Rules:**
  - Every agent has an agreed `commission_rate` (default 5.0% - 10.0%).
  - When an agent completes a booking, their wallet is automatically credited with the calculated commission amount:
    $$\text{Commission} = \frac{\text{Selling Price} \times \text{Commission Rate}}{100}$$
  - Agents can book against their available `wallet_balance` + approved `credit_limit`.

---

### 6. Specialized Services Desk
- **Primary Controller:** `App\Http\Controllers\ServiceController`, `Admin\VisaPassportController`
- **Models:** `VisaRequirement`, `VisaApplication`, `PassportApplication`, `SpecializedInquiry`
- **Portals:**
  - **Visa Assistance:** `/services/visa` & `/admin/visa`. Documents required per country, consulate processing fee, biometric appointments, and application status tracking.
  - **Indian Passport Seva:** `/services/passport` & `/admin/passport`. Normal and Tatkaal appointment booking assistance, document verification, and Police Verification tracking.
  - **Flight, Hotel, Train, Bus:** Dedicated enquiry collection forms for non-package travel requests.

---

### 7. Travel Operations & Trip Dispatch
- **Primary Controller:** `App\Http\Controllers\Admin\OperationsController` & `SupplierController`
- **Models:** `OperationsTrip`, `Supplier`, `Booking`
- **Key Views:**
  - `admin/operations/index.blade.php`, `show.blade.php`: Operational dispatch control for active departures.
  - `admin/suppliers/index.blade.php`, `create.blade.php`, `show.blade.php`, `edit.blade.php`: Supplier repository for DMCs, hoteliers, and fleet operators.
- **Operations Rules:**
  - Tour managers and transport drivers are assigned to confirmed departures.
  - Vehicle plate number, driver mobile, and emergency contacts are synced directly into the customer's downloadable Travel Voucher.

---

### 8. Accounts & GST Compliance
- **Primary Controller:** `App\Http\Controllers\Admin\AccountsController`
- **Models:** `Invoice`, `Payment`, `Expense`, `Setting`
- **Key Views:**
  - `admin/accounts/payments.blade.php`: Payment receipts ledger across UPI, Cards, Bank Transfers, and Cash.
  - `admin/accounts/invoices.blade.php`: GST tax invoice registry.
  - `admin/accounts/expenses.blade.php`: Direct supplier payments and overhead expenses.
  - `admin/accounts/gst_report.blade.php`: GSTR-1 and GSTR-3B preparation summaries for SAC 998555.
- **Taxation Standard:**
  - **SAC Code 998555:** Tour operator services provided to travelers.
  - GST Rate: 5% without Input Tax Credit (ITC) for bundled tour packages, split as 2.5% CGST + 2.5% SGST (intra-state) or 5.0% IGST (inter-state).

