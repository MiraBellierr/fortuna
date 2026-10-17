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
- [ ] **UI/UX Polish**
    - [x] Tailwind CSS refinements for a "Premium" financial look
    - [x] Form validation and user-friendly error messages
    - [ ] Loading states for charts and exports
- [ ] **Final Deployment Prep**
    - [ ] Environment configuration audit
    - [ ] Database indexing for performance (on `user_id` and `transaction_date`)
