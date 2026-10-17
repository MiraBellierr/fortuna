---
version: 1
slug: "resources-views-reports-index-blade-php"
primary_target: "resources/views/reports/index.blade.php"
related_targets: ["app/Http/Controllers/ReportPageController.php","resources/views/components/app-nav.blade.php"]
---

# Surface brief — resources/views/reports/index.blade.php (signed-in app family)

Scope: the Advanced Reports page — a year-scoped, detailed monthly and yearly analysis of the signed-in user's ledger. Visitor mode: Operate. It inherits the signed-in family's settled Swiss Minimal Light world and shell (64px top bar, 240px sidebar, the max-w-6xl content column); this surface decides only its composition, content, hierarchy, states, and interaction.

Audience/job/task/frequency:
- User: the single owner of the account, reviewing how a chosen year actually went — occasional use, not daily.
- Job: read the year's standing (income, spending, net saved, savings rate), see the shape of the year month by month, open a month's entries, find where the money went and came from, and compare years.
- Preserve all working behavior: single-user scoping, MYR display, server-rendered figures, the shared shell and nav; the flow chart reuses the existing reports API.

Content and constraints (user-confirmed 2026-10-17):
- Composition locked in the surface round from the dealt hand: The Working Register (time-first) — figures → full-width monthly flow chart → twelve-month ledger → category sheets → year-by-year comparison. The other dealt structures (The Delta Board, Money In/Money Out) were not chosen.
- Currency MYR with tabular figures; currency stated once per card; ApexCharts with animations disabled; a loading state for the flow chart; honest empty states for a year with no entries.
- No dark mode, no new colors: #2563EB as the one accent thread, income #94A3B8 and spend #2563EB on the chart with the net line #64748B dashed, green #15803D / red #DC2626 only as state flags on data.

Chosen direction: see Direction contract below. Memorable moment: the flow register — the year's income and spend columns with the dashed net line threading through, sitting above the ruled twelve-month ledger whose rows open their months in the transaction list.

Unresolved decisions: none blocking.

## Direction contract

THESIS: The reports page is the working register's annual spread: the year drawn once as flow, then set as a ruled twelve-month ledger with its categories and its comparison — it refuses the detached analytics-dashboard of floating widget grids.

OWN-WORLD: White ground #FFFFFF, #F8FAFC tinted panels inside 1px #E2E8F0 frames, #F1F5F9 inner rows, text #0F172A / #64748B, one accent #2563EB (active nav, links, spend columns, meter fills), income columns #94A3B8 and the net line #64748B dashed, green #15803D / red #DC2626 only as state flags on data, Inter 400–700 with tabular money, 12px surfaces / 8px controls, no shadows.

STORY: The user lands on the year they picked, reads the year's four answers in server-rendered figures, sees the year's shape in one flow chart, walks the twelve months in a ruled ledger — each month row opening that month in the transaction list — then checks where the money went and came from, and finally how the year compares with the years before it.

FIRST VIEWPORT: Page head "Reports" with the deck and the year select; four #F8FAFC figure cards (income, spent, net saved, savings rate — red only when negative, an em dash when there is no income to rate); below, the full-width "The year in months" sheet with its cap, the Income/Spend/Net legend, and the ApexCharts columns (income #94A3B8, spend #2563EB) under the dashes-only net line, with the twelve-month ledger beginning under the fold.

FORM: The Working Register — position 5 of 7 on the grounded list (the round's dealt card 3 of 3); seed:80949777540f0ad9.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance.
