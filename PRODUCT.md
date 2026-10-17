# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

The primary and only intended user is the owner of the app: a single person tracking their own income, expenses, and budgets. Each registered account is one person's private finances; sharing, households, and multiple concurrent users are out of scope unless the user changes this.

## Product Purpose

A personal finance and expense tracker. It lets the user record transactions (income vs expense, with receipts), organize spending into color-coded categories, set monthly budgets per category, and see actual-vs-budget progress and charts on a dashboard. Success: the user can quickly record an expense and confirm at a glance they are on budget.

## Positioning

Simplicity-first. Deliberate contrast with heavyweight finance platforms (Mint/YNAB-style): fast capture over deep analytics: record and check quickly, not a budgeting-philosophy suite. (Inferred from the user's interview answer; no other differentiator is binding.)

## Operating Context

- Stack roadmap and feature decisions live in `todo.md` (Project name: "Personal Finance & Expense Tracker").
- Built on Laravel 13, Blade templates, Laravel Breeze auth, Tailwind CSS, Alpine.js, ApexCharts; SQLite database; Vite build. Dev via `composer run dev`.
- Data model: User → Category → Transaction; Budget per category per month; receipts stored via Laravel Storage and served through secured access.

## Capabilities and Constraints

- Built: registration/login (Breeze), dashboard with stats & charts, transaction CRUD with search, advanced filtering/sorting, bulk delete, and receipt uploads, category CRUD with color picker, budgets overview with actual-vs-budget calculation and overshoot alerts, financial report aggregation, year-scoped reports page (monthly register, flow chart, category sheets, year comparison), profile/account settings.
- Planned (from `todo.md`, not yet built): exports page (CSV/PDF generation), settings (app preferences, currency), mobile-optimized quick-entry.
- Currency: displayed amounts default to MYR (Malaysian Ringgit). Confirmed in interview.
- Single-user scoping per account is a confirmed constraint.

## Brand Commitments

- Name: **Fortuna**. Confirmed by the user (2026-10-16); display the product as Fortuna in titles, navigation branding, and page copy.
- Existing tagline `Master Your Money` remains in use on the welcome page.

## Evidence on Hand

- `todo.md`: feature roadmap with phase checkboxes and done/undone state.
- Seeders and factories producing realistic financial data for testing.
- No testimonials, press, screenshots, or marketing material exist. Future work must not invent these.

## Product Principles

1. Capture speed over configuration depth: the fastest path from intent to recorded transaction wins.
2. The budget check is the product: actual-vs-budget must be legible at a glance on the dashboard.
3. One person's private data: every query and file access is scoped to the authenticated user.
4. Honest numbers: aggregates (totals, percentages, overshoot) reflect the stored truth; never fabricate or round away outliers.
