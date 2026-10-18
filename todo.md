# TODO List: Personal Finance & Expense Tracker

## 🏁 Phase 1: Foundation & Infrastructure
- [x] Project Scaffolding (Laravel 13)
- [x] Install Laravel Boost (AI Guidelines & Tools)
- [x] Define Database Schema (Categories, Transactions, Budgets)
- [x] Install Authentication (Laravel Breeze)
- [x] **FIX: Enable `pdo_sqlite` and `sqlite3` PHP extensions**
- [x] Run Database Migrations
- [x] Set up Base Model Relationships (User $\rightarrow$ Category $\rightarrow$ Transaction)
- [x] Create Model Factories & Seeders (for testing with realistic financial data)

## 💰 Phase 2: Core Financial Tracking
- [x] **Category Management**
    - [x] CRUD for categories (Income vs Expense)
    - [x] Category color picker (for chart visualization)
- [x] **Transaction Engine**
    - [x] Create/Edit/Delete transactions
    - [x] Income vs Expense toggle logic
    - [x] Date-based sorting and filtering
- [x] **Receipt Uploads**
    - [x] Implement file upload logic using Laravel Storage
    - [x] Link receipt files to transaction records
    - [x] Secure access to uploaded files

## 📈 Phase 3: Budgeting & Analytics
- [x] **Budgeting System**
    - [x] Monthly budget setup per category
    - [x] "Actual vs Budget" calculation logic
    - [x] Budget overshoot alerts/indicators
- [x] **Financial Reports (Backend)**
    - [x] Aggregate total income/expenses by month/year
    - [x] Calculate spending percentages per category
- [x] **Interactive Dashboard (Frontend)**
    - [x] Integrate Chart.js or ApexCharts
    - [x] Spending trend line chart (Monthly)
    - [x] Expense distribution pie chart (By Category)
    - [x] "Quick Stats" cards (Total Balance, Monthly Spend, Budget Remaining)

## 📄 Phase 4: Exports & Advanced Features
- [x] **Data Export**
    - [x] CSV Export of transaction history
    - [x] PDF Monthly Summary generator (using DomPDF or similar)
- [x] **Enhanced UX**
    - [x] Search and advanced filtering for transactions
    - [x] Bulk actions (e.g., delete multiple transactions)
    - [x] Responsive layout for mobile expense entry

## 🌐 Expected Pages

### Built
- [x] Login / Register (Breeze)
- [x] Dashboard (Stats & Charts)
- [x] Transactions Index (List, Filter, Sort)
- [x] Transaction Create/Edit
- [x] Categories Index (List & Manage)
- [x] Category Create/Edit
- [x] Budgets Overview (Actual vs Budget)
- [x] Budget Setup/Edit
- [x] Profile/Account Settings

### To Be Built
- [x] Advanced Reports Page (Detailed Monthly/Yearly Analysis)
- [ ] Exports Page (CSV/PDF Generation)
- [ ] Settings (App preferences, Currency, etc.)
- [ ] Mobile-Optimized Quick-Entry Page (simplified transaction form)

## 🧪 Phase 5: Quality Assurance & Polishing
- [x] **Testing Suite**

    - [x] Feature tests for Transaction CRUD
    - [x] Unit tests for Budget calculation logic
    - [x] Integration tests for Receipt uploads
- [x] **UI/UX Polish**
    - [x] Tailwind CSS refinements for a "Premium" financial look
    - [x] Form validation and user-friendly error messages
    - [x] Loading states for charts and exports
- [ ] **Final Deployment Prep**
    - [ ] Environment configuration audit
    - [ ] Database indexing for performance (on `user_id` and `transaction_date`)

## 🏗️ Architecture Deepening

_From the 2026-10-17 architecture review (candidate 1: "Give the budget check one module"). Plan agreed; not yet started._

- [ ] **Budget check read module**
    - [ ] One call on `Budget` — `checkFor($userId, $month)` — returns `{ lines, budgeted, spent, remaining }`
    - [ ] Lines carry spent, remaining, percent_used (uncapped int; null when no amount), is_over; ordered overshoots-first, then hardest-run
    - [ ] One grouped aggregate over transactions behind the call, replacing the three raw subqueries, the Eloquent variant, and the duplicated sort closures
    - [ ] Unify "Budget remaining" on the plan's arithmetic (spent within budgeted lines) — dashboard and PDF adopt it
- [ ] **Guarded write path for lines**
    - [ ] Migration: dedupe existing duplicates (keep most recently updated), then unique index on (user_id, category_id, month)
    - [ ] Create + change operations share one duplicate guard; duplicate → `ValidationException` (key `budget`)
    - [ ] Web adapter maps to `back()->withErrors`; JSON adapter lets it bubble to 422
- [ ] **Re-seat every call path on the module**
    - [ ] `DashboardController` — raw subquery, sort closure, remaining formula
    - [ ] `BudgetViewController` — raw subquery, sort closure, inline duplicate checks
    - [ ] `MonthlySummaryController` — raw subquery, sort closure
    - [ ] `BudgetAnalyticsController` — separate Eloquent aggregation
    - [ ] `BudgetAlertController` — both methods; kills the all-time-sum overshoot bug
    - [ ] Views read attributes only — percent math out of `dashboard.blade.php`, `budgets/index.blade.php`, `reports/monthly-summary.blade.php`
- [ ] **Glossary**
    - [ ] Create `GLOSSARY.md`: budget check, line, budget remaining (unified definition)
- [ ] **Tests**
    - [ ] Module test through the new seam (per-line math, totals, ordering)
    - [ ] First dashboard feature test (remaining math changes; it has none today)
    - [ ] First alerts-endpoint test (would catch the all-time-sum bug)
    - [ ] Existing page/PDF tests stay untouched — they are the safety net
- [ ] **Verification pass**
    - [ ] `composer install` (lockfile only), create `database/database.sqlite`, `php artisan migrate`
    - [ ] Run narrow tests, then the full suite; `vendor/bin/pint --dirty --format agent`

**Deferred candidates from the same review** (each needs its own planning round before implementation):
- [ ] Transaction payload + receipt lifecycle — one module over the Storage seam
- [ ] One authorization seam — policies for transaction and budget, category lifecycle owned once
- [ ] Report read models — one query, two adapters
- [ ] Unreferenced JSON surface — delete or re-seat, per the mobile quick-entry plan
