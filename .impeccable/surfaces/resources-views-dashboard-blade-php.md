---
version: 1
slug: "resources-views-dashboard-blade-php"
primary_target: "resources/views/dashboard.blade.php"
related_targets: ["resources/views/transactions/index.blade.php","resources/views/budgets/index.blade.php","resources/views/categories/index.blade.php","resources/views/profile/edit.blade.php","resources/views/profile/partials/update-profile-information-form.blade.php","resources/views/profile/partials/update-password-form.blade.php","resources/views/profile/partials/delete-user-form.blade.php","resources/views/layouts/app.blade.php","resources/views/components/sidebar.blade.php","resources/views/components/app-nav.blade.php"]
---

# Surface brief — resources/views/dashboard.blade.php (signed-in app family)

Scope: the authenticated app family in one pass — dashboard (primary), transactions, budgets, categories, profile — plus the shared shell (`layouts/app.blade.php`, `components/sidebar.blade.php`, `components/app-nav.blade.php`). Visitor mode: Operate.

Audience/job/task/frequency:
- User: one person (the account owner) in their own private ledger; daily capture, frequent quick checks, occasional setup.
- Job: record a transaction fast, then confirm at a glance whether the month is still inside its budget lines.
- Preserve all working behavior: CRUD forms, filters, receipt upload/serving, month selector, ApexCharts trend + distribution, Breeze profile flows, single-user scoping, validation and flash flows.

Content and constraints (user-confirmed 2026-10-17):
- Swiss Minimal Light is adopted for the signed-in family only; the welcome page and the auth sheets keep the Swiss Light world (Archivo, Fortuna Red, paper ground) by the user's explicit choice — a deliberate two-world product.
- Navigation stays a left sidebar. Dashboard summary figures and the budget check render server-side (first painted frame is final); charts keep their JS load.
- Currency display MYR, figures tabular; currency stated once per card. No dark-mode variants. Modals stay modal sheets; existing copy stays where it is product truth.

Chosen direction: Swiss Minimal Light, pinned by the user (2026-10-17): Inter, one blue accent `#2563EB`, white ground `#FFFFFF` with `#F8FAFC` surface panels, slate text (`#0F172A` / `#64748B`), state green `#16A34A` / red `#DC2626` for semantic flags only, 12-column grid, generous whitespace, four KPI cards over the budget check, charts below, thin `#E2E8F0` borders, no heavy shadows, flat buttons.
Memorable moment: the four-figure KPI row over the budget check — blue meters inside the lines, red flags past them; one blue thread per region (active nav, primary button, meter fill, the spending line).

Unresolved decisions: none blocking.

## Direction contract

THESIS: The signed-in app is the ledger itself in banking blue: record fast, check the lines at a glance. It keeps the ruled budget check as the hero while the pinned Swiss Minimal Light chrome — four figure cards, Inter, one accent — replaces the incumbent Archivo/Fortuna Red styling on these pages; it refuses the generic gradient analytics dashboard.

OWN-WORLD: White ground #FFFFFF, #F8FAFC tinted panels with 1px #E2E8F0 frames and #F1F5F9 inner rows, text #0F172A / #64748B, single accent #2563EB (primary buttons, active nav, meter fills, the spending line), semantic green #16A34A / red #DC2626 only for state flags, Inter 400-700 with tabular money, 12px surface / 8px control radii, no shadows, standard boxed controls.

STORY: The user records an entry and confirms the month's standing from stable, first-paint numbers in four cards and the ruled budget check; charts sit below, and every signed-in page reads as the same working product continued.

FIRST VIEWPORT: A white top bar (Fortuna wordmark left; date and mobile menu right) over a left sidebar; the content column opens with "Your ledger" and a one-line deck naming the month; then four #F8FAFC figure cards (total balance, income this month, spent this month, budget remaining — red figures only when past the line); below, the white budget-check card with ruled rows (name · spent/limit · blue meter, red flags past the line) and an MYR footer note; the chart cards follow.

FORM: Pinned-world adoption — the user pinned Swiss Minimal Light for the signed-in family; the pin settles world and composition (no decision round was dealt; a pin beats the roll). Composition per the brief: 12-column grid, four KPI cards over the budget check, charts below. seed:a7a54c1ae92d6f7c (surface roll recorded and corroborated with `concept-seed --check-seed`; the dealt hand was not put to a round because the pin pre-empted it)

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance.
