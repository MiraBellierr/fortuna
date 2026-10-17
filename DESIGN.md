---
name: Fortuna · Master Your Money
description: "One product, two deliberate worlds: a Swiss typographic report for welcome and auth, and a Swiss-minimal working register for the signed-in app."
colors:
  # Swiss Light (welcome + auth)
  ground: "#FAFAF8"
  paper: "#FFFFFF"
  ink: "#111111"
  ink-mid: "#6E6E6E"
  hairline: "#E4E4E2"
  hairline-inner: "#EFEFED"
  meter-track: "#F0F0EE"
  link-underline: "#C9C9C5"
  scrollbar-thumb: "#D6D6D2"
  accent: "#E3000F"
  accent-press: "#B8000C"
  # Swiss Minimal Light (signed-in app)
  app-ground: "#FFFFFF"
  app-surface: "#F8FAFC"
  app-surface-strong: "#F1F5F9"
  app-rule: "#E2E8F0"
  app-rule-inner: "#F1F5F9"
  app-ink: "#0F172A"
  app-ink-mid: "#64748B"
  app-ink-faint: "#94A3B8"
  app-ink-control: "#334155"
  app-primary: "#2563EB"
  app-primary-press: "#1D4ED8"
  app-primary-tint: "#EFF6FF"
  app-selection: "#BFDBFE"
  app-success-text: "#15803D"
  app-danger: "#DC2626"
  app-danger-deep: "#B91C1C"
  app-notice-success-bg: "#F0FDF4"
  app-notice-success-border: "#BBF7D0"
  app-notice-success-text: "#166534"
  app-notice-error-bg: "#FEF2F2"
  app-notice-error-border: "#FECACA"
  app-notice-error-text: "#B91C1C"
  app-quiet-slate: "#CBD5E1"
typography:
  # Swiss Light
  display:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "clamp(2.7rem, 5.4vw, 4.4rem)"
    fontWeight: 700
    lineHeight: 1.03
    letterSpacing: "-0.035em"
  headline:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "clamp(1.7rem, 3vw, 2.4rem)"
    fontWeight: 700
    lineHeight: 1.15
    letterSpacing: "-0.03em"
  title:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: "-0.02em"
  sheet-title:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "1.875rem"
    fontWeight: 700
    lineHeight: 1.15
    letterSpacing: "-0.03em"
  body:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "normal"
  label:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "12px"
    fontWeight: 600
    letterSpacing: "0.12em"
  wordmark:
    fontFamily: "Archivo, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontWeight: 700
    letterSpacing: "-0.03em"
  # Swiss Minimal Light
  app-page-title:
    fontFamily: "Inter, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "1.5rem"
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  app-figure:
    fontFamily: "Inter, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "26px"
    fontWeight: 600
    lineHeight: 1
    letterSpacing: "-0.01em"
    fontFeature: "tnum"
  app-heading:
    fontFamily: "Inter, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "15px"
    fontWeight: 600
    lineHeight: 1.5
  app-body:
    fontFamily: "Inter, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "14px"
    fontWeight: 400
    lineHeight: 1.6
  app-label:
    fontFamily: "Inter, 'Helvetica Neue', Helvetica, Arial, sans-serif"
    fontSize: "11px"
    fontWeight: 600
    letterSpacing: "0.08em"
rounded:
  sharp: "0px"
  swatch: "3px"
  file-control: "6px"
  control: "8px"
  notice: "10px"
  surface: "12px"
  pill: "999px"
spacing:
  stack: "32px"
  block: "40px"
  block-lg: "48px"
  section-top: "64px"
  section-top-lg: "96px"
  section-bottom-tight: "80px"
  section-bottom: "96px"
  section-bottom-lg: "112px"
  gutter: "24px"
  app-stack: "32px"
  app-panel: "20px"
  app-grid: "16px"
  app-gutter: "24px"
  app-row: "16px"
components:
  button-primary:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.paper}"
    typography: "600 15px 'Archivo'"
    rounded: "{rounded.sharp}"
    padding: "14px 28px"
  button-primary-hover:
    backgroundColor: "{colors.accent-press}"
  button-primary-small:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.paper}"
    typography: "600 15px 'Archivo'"
    rounded: "{rounded.sharp}"
    padding: "12px 16px"
  button-primary-sheet:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.paper}"
    typography: "600 15px 'Archivo'"
    rounded: "{rounded.sharp}"
    padding: "15px 0"
    width: "100%"
  quiet-link:
    textColor: "{colors.ink}"
    typography: "500 15px 'Archivo'"
  specimen-table:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.sharp}"
    padding: "20px"
  specimen-sheet:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.sharp}"
  field-input:
    textColor: "{colors.ink}"
    typography: "400 15px 'Archivo'"
    rounded: "{rounded.sharp}"
    padding: "5px 0 1px"
  meter:
    backgroundColor: "{colors.meter-track}"
    size: "4px"
  wordmark:
    textColor: "{colors.ink}"
    typography: "700 1.5rem 'Archivo', -0.03em"
  app-button-primary:
    backgroundColor: "{colors.app-primary}"
    textColor: "{colors.app-ground}"
    typography: "600 14px 'Inter'"
    rounded: "{rounded.control}"
    padding: "10px 16px"
  app-button-primary-hover:
    backgroundColor: "{colors.app-primary-press}"
  app-button-block:
    backgroundColor: "{colors.app-primary}"
    textColor: "{colors.app-ground}"
    typography: "600 14px 'Inter'"
    rounded: "{rounded.control}"
    padding: "12px 0"
    width: "calc(100% - 40px)"
  app-button-danger-block:
    backgroundColor: "{colors.app-danger}"
    textColor: "{colors.app-ground}"
    typography: "600 14px 'Inter'"
    rounded: "{rounded.control}"
    padding: "12px 0"
    width: "calc(100% - 40px)"
  app-button-delete-block:
    backgroundColor: "{colors.app-ground}"
    textColor: "{colors.app-ink}"
    typography: "600 14px 'Inter'"
    rounded: "{rounded.control}"
    padding: "11px 0"
    width: "calc(100% - 40px)"
  app-field-label:
    textColor: "{colors.app-ink-control}"
    typography: "500 13px 'Inter'"
  app-field-input:
    backgroundColor: "{colors.app-ground}"
    textColor: "{colors.app-ink}"
    typography: "400 14px 'Inter'"
    rounded: "{rounded.control}"
    padding: "9px 12px"
  app-sheet:
    backgroundColor: "{colors.app-ground}"
    textColor: "{colors.app-ink}"
    rounded: "{rounded.surface}"
    padding: "20px"
  app-figure-card:
    backgroundColor: "{colors.app-surface}"
    textColor: "{colors.app-ink}"
    rounded: "{rounded.surface}"
    padding: "20px"
  app-notice-success:
    backgroundColor: "{colors.app-notice-success-bg}"
    textColor: "{colors.app-notice-success-text}"
    rounded: "{rounded.notice}"
    padding: "12px 16px"
    typography: "400 13.5px 'Inter'"
  app-notice-error:
    backgroundColor: "{colors.app-notice-error-bg}"
    textColor: "{colors.app-notice-error-text}"
    rounded: "{rounded.notice}"
    padding: "12px 16px"
    typography: "400 13.5px 'Inter'"
  app-meter:
    backgroundColor: "{colors.app-surface-strong}"
    size: "6px"
    rounded: "{rounded.pill}"
  app-quiet-link:
    textColor: "{colors.app-ink-mid}"
    typography: "500 14px 'Inter'"
  app-wordmark:
    textColor: "{colors.app-ink}"
    typography: "700 1.25rem 'Inter', -0.02em"
  app-small-label:
    textColor: "{colors.app-ink-mid}"
    typography: "600 11px 'Inter', 0.08em"
  app-nav-link:
    textColor: "{colors.app-ink-mid}"
    typography: "500 14px 'Inter'"
    rounded: "{rounded.control}"
    padding: "10px 12px"
  app-nav-link-active:
    backgroundColor: "{colors.app-primary-tint}"
    textColor: "{colors.app-primary-press}"
---

# Design System: Fortuna · Master Your Money

## Overview

**Creative North Star: "Swiss Light"** — one philosophy, two deliberate worlds: the public report and the working register.

Fortuna looks like a Swiss typographic report: flat, gridded, unhurried, and precise. Nothing floats, nothing glows, nothing bounces. Every claim sits flush-left against a near-white paper ground (#FAFAF8), separated by hairline rules rather than boxed into cards, set in one grotesque family (Archivo) at tighter tracking as sizes grow. This is deliberate contrast with the hero-over-card-row conversion template the product refuses; the persuasion job is done by an inset specimen ledger table (hairline-ruled, tabular numerals, one overshoot flagged in the accent) because in this world an honest, well-set table is more persuasive than a testimonial could ever be (no testimonials exist, and none may be invented).

Trust is carried by restraint: shallow-gray secondary text, generous whitespace, one strong signal accent rationed to actions and flags, and data that always reports straight. The page is stable and flat by design: there is no entrance animation; the first painted frame is the final state. Since the first recording, the world has grown from the front page to the signed-out family: the auth pages are the same ground and hairlines with one specimen sheet where the front page sets a report.

Since 2026-10-17 the product deliberately runs two worlds. The signed-in family was rebuilt in Swiss Minimal Light, the world the user pinned: a white ground, #F8FAFC tinted panels inside 1px #E2E8F0 frames, Inter with tabular money, and one accent — banking blue #2563EB — running as a single thread per region (active nav, primary actions, meter fills, the expense line). It is the same restraint in a workday register: no shadows at rest, structure drawn at 1px, green and red kept strictly as state flags on data. The signed-in app refuses the generic gradient analytics dashboard; the ruled budget check stays the hero, and the four figure cards and charts serve it. Welcome and auth keep Swiss Light unchanged: the two worlds are a committed product decision, not a migration in progress.

**Key Characteristics:**
- Near-white paper ground, pure-ink text, single strong red accent reserved for response (Swiss Light).
- Hairline rules replace cards, shadows, glass, and gradient surfaces entirely (Swiss Light).
- One self-hosted grotesque (Archivo, 400–700); display sizes track negative and set with `text-wrap: balance` (Swiss Light).
- The working register (Swiss Minimal Light): white ground, #F8FAFC panels inside 1px #E2E8F0 frames, boxed controls at 8px, surfaces at 12px, Inter 400–700, 26px/600 figures.
- One blue thread: #2563EB is the app's only accent; green (#15803D and the notice greens) and red (#DC2626) appear only as state flags on data.
- Tabular numerals (`.tnum`) on every money figure in both worlds, so columns align like a ledger.
- Flat stability in both worlds: no entrance animation, no resting shadows; the only motion is 140ms state feedback, and the app's charts ship with animations disabled.
- Data honesty: numbers reflect stored truth; downloaded/announced features are labeled as notices, never shipped claims.
- One sheet per signed-out page: the auth family repeats the front page's paper, hairlines, caption tags, and single red submit (Swiss Light).
- Two deliberate worlds, one discipline: a surface belongs to exactly one world (The Two-World Rule), and structure is drawn in both.

### Scope: two worlds (read before editing any view)

Swiss Light — unchanged by the app build. Front page: `resources/views/welcome.blade.php`. Auth family: `resources/views/layouts/guest.blade.php` (the shared shell) plus login, register, forgot-password, reset-password, confirm-password, and verify-email (surface brief `.impeccable/surfaces/resources-views-auth-login-blade-php.md`; finish-reviewed to ship).

Swiss Minimal Light — adopted 2026-10-17 on the user's pin; finish-reviewed to ship (seven material fixes scored resolved). The signed-in family: the shared shell `resources/views/layouts/app.blade.php` (the token layer — `:root` custom properties — and every component rule live inline in its `<style>`), `dashboard.blade.php`, `transactions/index.blade.php`, `budgets/index.blade.php`, `categories/index.blade.php`, `reports/index.blade.php`, `profile/edit.blade.php` plus the three `profile/partials/*` forms, and the shared nav `components/sidebar.blade.php` / `components/app-nav.blade.php`. Surface briefs `.impeccable/surfaces/resources-views-dashboard-blade-php.md` (the shell and dashboard) and `resources-views-reports-index-blade-php.md` (the year-scoped reports register, finish-reviewed to ship); evidence `.impeccable/review/app-*.png` (38 rasters, each with a provenance sidecar beside it — ten from the reports finish review).

Leftovers in the tree, referenced by no view: the stock Breeze component set (`x-header`, `x-modal`, `x-nav-link`, `x-dropdown*`, `x-responsive-nav-link`, `x-input-label`, `x-input-error`, `x-secondary-button`, `x-danger-button`, `x-primary-button`, `x-text-input`, `x-auth-session-status`, `x-application-logo`) and `layouts/navigation.blade.php` — dead scaffolding in the starter's gray/indigo styling, not a precedent to inherit. No Figtree / fonts.bunny.net references remain anywhere; `tailwind.config.js` leads its sans stack with Inter. Every view head still calls `@fonts` (Vite's font manifest; no manifest ships, so it emits nothing) and each world's shell self-declares its faces: Archivo in the welcome and guest shells, Inter in `layouts/app.blade.php`.

The Two-World Rule governs placement: a surface belongs to exactly one world. New signed-in surfaces extend Swiss Minimal Light; new public/signed-out surfaces extend Swiss Light; cross-world mixing — blue in the auth sheet, Fortuna Red in the app, 0px corners in the app, boxed 8/12px radii in the report — is drift.

## Colors

A two-world palette: the report's paper neutrals with one signal red, and the working register's slate neutrals with one banking blue and two semantic flags.

### Primary
- **Fortuna Red** (#E3000F) [Swiss Light]: the only accent. Reserved for the primary action button (front-page CTA and the auth sheet's full-bleed submit), overspent figures and meter fills, validation error messages, `::selection` (background, white text), and `:focus-visible` outlines. Never used decoratively or on resting text.
- **Red Press** (#B8000C) [Swiss Light]: the hover/press state of the accent button (both button variants). The only "second" accent value that exists.
- **Ledger Blue** (#2563EB) [Swiss Minimal Light]: the app world's only accent — primary buttons and sheet submits, the checked type toggle, active nav labels on their tint, meter fills, the field focus border and ring, the caret, and the spend series on the charts (the trend's expense line, the reports spend columns). One thread per region; never decoration.
- **Ledger Blue Press** (#1D4ED8) [Swiss Minimal Light]: hover and press of every blue button; also active labels on Blue Tint — the nav and the reports year rows.
- **Blue Tint** (#EFF6FF) [Swiss Minimal Light]: the active nav fill, the checked segmented-toggle fill, and the reports comparison's selected-year row — the only filled blue surfaces besides the accent itself.
- **Selection Blue** (#BFDBFE) [Swiss Minimal Light]: text-selection wash (`::selection`) with ink text on top.

### Secondary

Semantic flags exist only in the app world, and each one is state on data, never chrome.

- **Growth Green** (#15803D) [Swiss Minimal Light]: income figures in lists and tables. The shell also declares `--success` (#16A34A); no shipped surface renders it yet, so success as painted is #15803D plus the notice green trio below.
- **Danger Red** (#DC2626) [Swiss Minimal Light]: overshoot amounts and meter fills, field errors, destructive hover text, and the danger button's fill.
- **Danger Deep** (#B91C1C) [Swiss Minimal Light]: the danger button's hover fill and the error notice's text.
- **Notice Green trio** (#F0FDF4 ground / #BBF7D0 border / #166534 text) [Swiss Minimal Light]: the success notice sheet on transactions, budgets, categories, and profile.
- **Notice Red trio** (#FEF2F2 / #FECACA / #B91C1C) [Swiss Minimal Light]: the error notice sheet.

### Neutral
- **Paper Ground** (#FAFAF8) [Swiss Light]: page and background ground; also the scrollbar track.
- **Sheet White** (#FFFFFF) [Swiss Light]: the specimen figure and auth sheet background (the one surface that lifts off the ground, by tonal step only, never by shadow).
- **Ink** (#111111) [Swiss Light]: all primary text, the wordmark, the ink fill inside meters, 2px section-setting rules, and the skip-link's background.
- **Info Gray** (#6E6E6E) [Swiss Light]: all secondary text: decks, data figures, notices, list copy. The report's most-used non-ink value.
- **Hairline** (#E4E4E2) [Swiss Light]: the outer rule register: figure and sheet borders, figcaption/figure footers, the sheet caption strip and footnote, header top rule, column separators, footer top rule, the small-label underline in the notices column.
- **Row Hairline** (#EFEFED) [Swiss Light]: the inner rule inside the specimen figure and the auth sheet, lighter than outer hairlines so table rows and field rows read subordinate to the frame.
- **Meter Track** (#F0F0EE) [Swiss Light]: the 4px budget-progress track behind the ink/accent fill.
- **Underline Gray** (#C9C9C5) [Swiss Light]: quiet-link underlines at rest, list-disc bullet markers, and the auth checkbox border; the underline darkens to ink on interaction-by-proximity.
- **Scrollbar Thumb** (#D6D6D2) [Swiss Light]: browser chrome styling only (rounded thumb inset 3px in the ground color). A token, but chrome-scoped; never render it as a component surface.
- **Working White** (#FFFFFF) [Swiss Minimal Light]: the working ground — page, top bar, sheets, cards, inputs, dialog sheets. The same value as Sheet White, but a different world's ground.
- **Panel Slate** (#F8FAFC) [Swiss Minimal Light]: figure-card fill, nav hover fill, the records table's header band, the monogram chip.
- **Row Slate** (#F1F5F9) [Swiss Minimal Light]: the app's inner register — row dividers, the mobile-menu divider, meter tracks, the file-button fill. Declared twice in the shell CSS (`--rule-inner`, `--surface-strong`) with one value by design.
- **Frame Slate** (#E2E8F0) [Swiss Minimal Light]: the outer 1px register — sheet, card, and dialog frames, top bar and footer rules, the table frame, control borders, the chart grid.
- **Slate Ink** (#0F172A) [Swiss Minimal Light]: app text, and the dialog scrim at 40%.
- **Secondary Slate** (#64748B) [Swiss Minimal Light]: decks, notes, meta, at-rest amounts, legend and axis text.
- **Control Slate** (#334155) [Swiss Minimal Light]: field labels, toggle labels, control and legend text — the register between ink and secondary.
- **Faint Slate** (#94A3B8) [Swiss Minimal Light]: the spent/limit separator, disabled pagination and delete, and the income series on the charts — the trend line and the reports columns.
- **Edge Slate** (#CBD5E1) [Swiss Minimal Light]: quiet-link underlines at rest, the scrollbar thumb, hover borders on boxed controls.

### Named Rules

**The One Signal Rule.** Fortuna Red appears only in response positions: the primary button, an active overshoot, a validation error, focus, and text selection. It is never resting-state text and never decoration. On any Swiss Light surface its total area stays small by design: that scarcity is what makes the overshoot, the error, and the one red submit read.

**The Hairline Doctrine.** In the Swiss Light world, structure is drawn, not built. No `box-shadow`, no `backdrop-filter`, no gradient panel, no rounded card. A container boundary is a 1px #E4E4E2 line; a section boundary is 1px #111111; row-versus-frame is two levels of hairline (#EFEFED inside, #E4E4E2 outside).

**The One Blue Thread Rule.** In the app world #2563EB is the only accent, and it runs as one thread per region — active nav, the primary action, meter fills, the expense line, focus. Semantic green and red never leave the data they flag. No second accent, no decorative fill: scarcity is what makes "past the line" read in red.

**The Two-World Rule.** Each shipped surface declares its world and stays in it: welcome and auth extend Swiss Light (Archivo, Fortuna Red, paper ground); signed-in pages extend Swiss Minimal Light (Inter, Ledger Blue, Working White). Tokens do not cross. Adding a surface starts by choosing its world, not by blending two.

## Typography

**Display Font:** Archivo (self-hosted latin-subset woff2 at 400/500/600/700 in `public/fonts/`, loaded via the four `@font-face` blocks carried in the welcome and guest-layout `<style>` blocks; fallback `'Helvetica Neue', Helvetica, Arial, sans-serif`). Offline-first: no CDN in any Swiss Light page; the `@fonts` directive reads Vite's font manifest (`public/build/fonts-manifest.json` / `fonts-manifest.dev.json` beside `public/hot`) but no manifest ships yet, so the shells self-declare. — Swiss Light (welcome + auth).
**Working Font:** Inter (self-hosted latin subsets at 400/500/600/700 in `public/fonts/inter-*.woff2`, declared via four `@font-face` blocks inline in `layouts/app.blade.php`; `tailwind.config.js` leads the sans stack with Inter; fallback `'Helvetica Neue', Helvetica, Arial, sans-serif`). Offline-first: no CDN in the app world either. — Swiss Minimal Light (signed-in app).
**Label/Mono Font:** none in either world; labels and data figures are the world's own grotesque, and alignment comes from `font-variant-numeric: tabular-nums` (`.tnum`), not a mono face.

**Character:** One grotesque voice, tightened at scale. Small sizes open up to 1.7 line-height in gray; display sizes close down to 1.03 and track −0.035em. The voice is a well-set broadsheet: everything shares one objective register, and the money columns are set with the same care as the headlines. The working register adds a second voice at the same discipline: Inter at a flat 14px working size, a 26px/600 figure display for the numbers that answer the month, and 11px uppercase labels. Hierarchy comes from weight and register, never from size theatre.

### Hierarchy
- **Display** (700, `clamp(2.7rem, 5.4vw, 4.4rem)`, 1.03, −0.035em, `text-wrap: balance`) [Swiss Light]: the page-level claim only: one such headline per major band, flush left, two lines against the 8-column side of the 12-column grid.
- **Headline** (`section-head`: 700, `clamp(1.7rem, 3vw, 2.4rem)`, 1.15, −0.03em, balanced) [Swiss Light]: section titles, always set under a 2px ink rule.
- **Title** (`story-head`: 600, 1.25rem, 1.3, −0.02em) [Swiss Light]: column and feature heads inside the thirds.
- **Sheet Title** (`sheet-title`: 700, 1.875rem (30px), 1.15, −0.03em, balanced) [Swiss Light]: the one headline inside an auth sheet (fixed rather than clamped, since the sheet itself is fixed at 480px).
- **Body** (400, 16px, 1.6) [Swiss Light]: paragraphs; feature copy runs 15.5px/1.7 in Info Gray; opening deck runs 17–18px/1.7 in Info Gray, capped at ~58ch.
- **Action** (600, 15px, 1.6) [Swiss Light]: button labels and quiet links.
- **Data** (400/500/600, 14px, `.tnum`) [Swiss Light]: ledger figures; 14px numerals in gray except the flagged overspend (600, Fortuna Red).
- **Label** (`small-label`: 600, 12px, +0.12em, uppercase) [Swiss Light]: figcaption tags ("This month", "SPECIMEN"), header/footer nav, the "In later editions" caption, copyright line, and the auth sheet's caption tags and field captions.
- **Page Title** (`sheet-title`: Inter 600, 1.5rem (24px), 1.25, −0.02em) [Swiss Minimal Light]: the one headline per signed-in page ("Your ledger", "Budgets", "Categories", "Profile", "Transaction history").
- **Figure** (`figure-value`: Inter 600, 26px, 1, −0.01em, `.tnum`) [Swiss Minimal Light]: headline money in the figure cards, prefixed by a muted 13px `MYR`; red only past the line.
- **Card Heading** (Inter 600, 15px) [Swiss Minimal Light]: sheet captions and card heads, with a 13px tabular meta on the cap's right.
- **App Body** (Inter 400, 14px, 1.6) [Swiss Minimal Light]: working copy, tables, and row meta; notes run 13px, foot notes 12.5px, both in Secondary Slate.
- **App Money** (Inter 400–600, 13–14px, `.tnum`) [Swiss Minimal Light]: row figures; 600 + Danger Red only for overshoots.
- **App Label** (`small-label`: Inter 600, 11px, +0.08em, uppercase) [Swiss Minimal Light]: figure-card metric names, notice flags ("Noted" / "Error"), the footer line, the top-bar date.

### Named Rules

**The Tabular Numbers Rule.** Any figure that means money gets `.tnum`, in both worlds. Non-aligned numerals in a data row are a bug, not a style choice.

**The White-Out Rule.** The specimen figure and its ledger rows are typographic only: no numbered chips, no icon dots, no sparkline ornament. Attention goes to the numbers and the flagged row. (Swiss Light.)

**The Figure Register Rule.** In the app world headline numbers are set in the 26px/600 tabular figure display on the figure cards; they are never shrunk into chips, icon-decorated, or animated. Row money stays 13–14px `.tnum`; the display register is reserved for the page's standing — the dashboard's month, the reports year — never the rows.

## Layout

**Swiss Light (welcome + auth).** The page runs as a continuing printed report inside a maximum-width column (`max-w-6xl`, 1152px) with 24px gutters (`px-6`). Structure is 12-column CSS Grid (`md:grid-cols-12`):

- **Opening band:** 8/4 split. Left 8 columns carry display headline, deck, and action row (`md:pr-16` keeps column discipline); right 4 columns hold the specimen ledger. `gap-y-14` handles the stacked collapse.
- **Feature band:** three equal thirds separated by vertical hairlines (`md:border-r #E4E4E2`) and by horizontal padding (`md:px-10` / `md:pr-10` / `md:pl-10`). No gutter class, the rule is the gutter.
- **Closing band:** an 8/4 repeat; the right column is the announced-work notices list under a hairline-underlined small-label.
- **Section rhythm:** each new band opens with a `border-t border-[#111111]` (1px, ink) then generous headroom (`pt-10` under headline rules, `pt-14` on the closing band); vertical spacing runs `pt-16 md:pt-24` at the top of the page and `pb-20 / pb-24 / pb-28` between and after bands.
- **Top bar:** container-height 68px (`h-[68px]`), bottom border 2px ink (`border-b-2 border-b-[#111111]`).
- **Footer:** 76px strip (`h-[76px]`), 1px hairline top border, wordmark left, small-label copyright right.
- **Auth pages (guest shell):** the same top bar (68px, 2px ink bottom rule) and footer (76px hairline; it stacks to a `flex-col` with `gap-3` on mobile); the body is a min-h-screen flex column so the footer sits at the page foot on short pages. Below the bar, one centred column (`max-w-[528px]` including `px-6` gutters, i.e. a 480px sheet on desktop and full-width-minus-48px on mobile) set `pt-16 md:pt-24`. Nothing else occupies the ground: the sheet, then a `mt-5` quiet cross-link under it.
- **Sheet internals:** caption strip `16px 24px 12px` over a hairline; body padding `24px 24px 10px`; field rows padded `13px 0 12px` on inner hairlines, the last row borderless; the submit is full-bleed (100% × 15px); the footnote `12px 24px` under a hairline.
- **Mobile (390px):** the header button tightens (`max-sm:px-4 max-sm:py-3`), "Log in" hides (`hidden sm:inline`), and every grid collapses to single-column with gap-based vertical rhythm. A fixed skip link appears on first focus.

**Swiss Minimal Light (signed-in app).** The working register runs its own shell around the same `max-w-6xl` column (the pin's "12-column grid" landed as the responsive card grids below, not a 12-track template):

- **App shell:** a sticky 64px top bar (`h-16`, 1px Frame Slate bottom rule) — wordmark left; the date (small-label, tabular, `md+`) and the mobile `Menu` control (below `lg`) right. Below it: a 240px white sidebar (`lg+`) with its own right rule, sticky under the bar (`top-16`, height viewport minus the bar), holding the nav and the identity foot; then the content column, `max-w-6xl` (1152px) with 24px gutters (`px-6`) and `py-8 md:py-12`.
- **Section rhythm:** every block opens at `mt-8` (32px); card grids use 16px gaps (`gap-4`); filter forms 24px column / 16px row gaps.
- **Dashboard composition:** four figure cards (1 / sm:2 / xl:4 across) over the budget-check sheet, then both charts in a 1 / lg:2 grid. Budgets and Categories open with three figure cards (1 / sm:3).
- **Reports composition:** the year head (title, deck, year select) over the year's four figures on the same 1 / sm:2 / xl:4 row, then full-width sheets in reading order — the flow ("The year in months"), the twelve-month ledger — then the two category sheets in a 1 / lg:2 pair, then the full-width comparison ("The years"). One register sheet per question.
- **Sheets:** cap `14px 20px` over the inner rule; body 20px; foot `12px 20px` at 12.5px. Dialog sheets cap at `max-w-[480px]`, centred at `sm:py-12` over the scrim.
- **Records:** below `sm` each transaction is a stacked row; from `sm` up, a real table (`min-w-[760px]`, horizontally scrollable) under a Panel Slate header band. Every record carries a selection checkbox (mirrored across the two layouts); the bulk band (count · Select all N on this page · Clear selection · Delete selected) rides inside the sheet from `sm` up and fixes to the viewport foot below `sm` while anything is selected.
- **Mobile (app):** below `lg` the sidebar is replaced by a full-screen white menu under the top bar (`Menu` ⇄ `Close`), built from the same nav and identity block; figure grids stack to one column.

### Named Rules

**The One Sheet Rule.** A signed-out page is one specimen sheet centred on the ground (caption tags, headline, deck, hairline field rows, one full-bleed accent submit, hairline footnote), with a single quiet cross-link under it. No split hero, no side panel, no second card; if the content doesn't fit one sheet, it isn't an auth page. (Swiss Light.)

**The Figures-First Rule.** A signed-in page opens with its standing as figures: the dashboard's four cards (total balance, income this month, spent this month, budget remaining) sit above the budget check, and the charts sit below it. Those numbers are server-rendered, so the first painted frame is the answer; the check comes next, the evidence after. (Swiss Minimal Light.)

## Elevation & Depth

**Swiss Light is flat, deliberately and completely.** There is no visible shadow in that world, and that absence is a stated invariant, not an omission: depth is communicated by tonal layering (Sheet White figure or sheet on Paper Ground), drawn hairlines (outer #E4E4E2 vs inner #EFEFED register), a 2px ink section rule where a physical break is wanted, and whitespace in quantities that let single figures breathe. If a mock asks for a shadow, the correct translation is usually one more hairline. The only `box-shadow` declarations in the Swiss Light CSS are invisible plumbing: `box-shadow: none` suppressions on focused fields/checkboxes, and Chrome's autofill repaint (`-webkit-box-shadow: 0 0 0 1000px #FFFFFF inset`) that keeps autofilled fields sheet-white instead of the browser's yellow.

**Swiss Minimal Light carries the same flatness.** Nothing lifts, and nothing casts at rest. Depth is a tone step (#F8FAFC panels on Working White, #F1F5F9 rows inside white sheets), the 1px #E2E8F0 frame, and exactly one float: the dialog sheet over a `#0F172A` 40% scrim. Its only `box-shadow` uses are state and plumbing: the 3px `rgba(37,99,235,0.15)` focus ring that replaces the outline on boxed fields (a state indicator, never elevation), `box-shadow: none` suppression on the focused box, and the same autofill repaint.

### Named Rules

**The Flat Surface Rule.** Surfaces never lift, in either world. If something must feel separate, separate it with a rule or a tone step (#FFFFFF on #FAFAF8 in the report; #F8FAFC on #FFFFFF in the app). Shadows are not in the vocabulary; a hover may deepen color, never cast.

**The Flat Stability Rule.** Swiss Light surfaces do not animate in. By design, the first painted frame is the final state: no entrance transitions, no reveal effects, no scroll-driven movement. The only motion in the system is responsive state feedback (140ms ease-out on hover of buttons/links) and the reduced-motion kill switch (`@media (prefers-reduced-motion: reduce) { * { transition: none !important; } }`, shipped in both shells). Stability here is an honesty signal: numbers first, presentation stable. The app world keeps the rule — its figures are server-rendered and its ApexCharts charts ship with animations disabled.

**The Only Float Rule.** In the app world exactly one thing floats: the dialog sheet, over a 40% Slate Ink scrim. It separates itself by the scrim and by focus — never by shadow, blur, or a lift.

## Shapes

**Swiss Light** is sharp by commitment: corner radius is 0 on every surface, container, rule, and button; verified through the `rounded` token `sharp: "0px"`. Repetition is rules, not boxes: outer hairline frames the specimen figure; inner row hairlines (lighter #EFEFED) divide account rows; column separators are vertical hairlines; section rules under heads and the header's border-b-2 ink rule create hierarchy by weight (2px ink > 1px #E4E4E2) rather than by shadow or color. The auth sheet continues the language: the field is not a box at all: a small-label caption above a transparent, borderless input, closed by the next hairline; the only rectangle in the auth world is the sheet frame itself. The only radius in the Swiss Light world lives in browser chrome: the 8px scrollbar thumb. That is styling the scrollbar, not licensing rounded corners into components.

**Swiss Minimal Light boxes its forms instead:** 12px on surfaces (sheets, figure cards, dialog sheets), 10px on notices, 8px on controls (buttons, inputs, selects, nav links, the monogram chip), 6px on the file-attach button, 3px on the category color chip, 999px on meters and the scrollbar thumb. Both geometries are load-bearing identities: the report's 0px and the app's 12/8 never cross worlds (The Two-World Rule); a rounded card on the welcome page or a sharp-cornered sheet in the app is a bug, not a variation.

## Components

### Buttons
- **[Swiss Light]** **Shape:** sharp corners (0px radius), flat fill, no border, no shadow at rest.
- **[Swiss Light]** **Primary** (`btn-primary`): Fortuna Red fill (#E3000F), white label, 600 15px, padding 14px 28px (the header button tightens on mobile to 12px 16px). Hover: **Red Press** (#B8000C), 140ms ease-out on `background-color`. Focus: 2px Fortuna Red outline, 3px offset (`:focus-visible`), same as every interactive element.
- **[Swiss Light]** **Sheet Submit** (`btn-primary` on auth sheets): the same accent fill, laid full-bleed (100% width, 15px 0 padding, centred 600 15px label) between the last field row and the sheet footnote. Exactly one per sheet; it is the page's only solid red.
- **[Swiss Light]** **Quiet Link** (`quiet-link`): ink text, 500 15px, 1.5px underline at 4px offset in Underline Gray (#C9C9C5); hover slides the `text-decoration-color` to ink, 140ms ease-out. The underline is the hover affordance; the text itself never changes color.
- **[Swiss Minimal Light]** **Primary** (`btn-primary`): 8px radius, solid Ledger Blue (#2563EB), white 600 14px label, padding 10px 16px; hover Ledger Blue Press (#1D4ED8), 140ms ease-out; focus is the shared 2px #2563EB outline at 2px offset.
- **[Swiss Minimal Light]** **Sheet Submit** (`btn-block`): the same blue, inset at the foot of a sheet — `width: calc(100% - 40px)` with 20px side margins and 12px 0 padding ("Save entry", "Set the budget"). One per dialog sheet.
- **[Swiss Minimal Light]** **Danger Submit** (`btn-danger-block`): the same geometry on Danger Red (#DC2626), hover #B91C1C — destructive confirmation only (delete account).
- **[Swiss Minimal Light]** **Delete Trigger** (`btn-delete-block`): white fill with a 1px Frame Slate border and an ink label; hover moves border, background, and label into the red register (#FECACA / #FEF2F2 / #DC2626), 140ms. Quiet at rest, red under the cursor (see `.impeccable/review/app-desktop-delete-trigger-hover-closeup.png`).
- **[Swiss Minimal Light]** **Quiet Link** (`quiet-link`): Secondary Slate, 500, 1px underline in Edge Slate (#CBD5E1) at 3px offset; hover darkens both text (to Slate Ink) and underline (to Faint Slate), 140ms. Sizes 12.5–14px by placement.
- **[Swiss Minimal Light]** **Inline actions** (Edit, View, Clear, Cancel, Previous, Next, Export CSV, Monthly summary (PDF)): 13px/500 Secondary Slate, hover Slate Ink; Delete hovers to Danger Red; disabled states drop to Faint Slate ("Previous"/"Next" at the ends of the record list).

### Inputs / Fields
- **[Swiss Light]** **Style:** no box. A hairline row (`13px 0 12px` padding) closed by 1px Row Hairline (#EFEFED), with an uppercase small-label caption in Info Gray above a borderless, transparent input (400 15px Archivo, 1.4, ink). The last field in a sheet drops its bottom hairline; the sheet frame supplies the edge. Placeholder ink is a light #B0B0AC (placeholder only, never a label substitute).
- **[Swiss Light]** **Focus:** the shared 2px Fortuna Red outline at 3px offset (`:focus-visible`), with every default ring/glow suppressed (`box-shadow: none`); the outline is the only focus signal.
- **[Swiss Light]** **Error:** validation text under the row, set 12.5px/1.5 in Fortuna Red (`.field-error`) and paired with `aria-invalid` on the input. The frame itself does not change color; the red message is the flag.
- **[Swiss Light]** **Autofill:** Chrome's autofill is repainted sheet-white via an inset `box-shadow` so filled fields keep the world's ground.
- **[Swiss Minimal Light]** **Style:** a standard box — white fill, 1px Frame Slate border, 8px radius, padding 9px 12px, 400 14px Inter, ink text; the 13px/500 Control Slate caption sits above (6px gap). Placeholder Secondary Slate. Fields stack on 16px margins. Below 640px the boxed field text opens to 16px so iOS does not zoom the page on focus.
- **[Swiss Minimal Light]** **Focus:** border shifts to Ledger Blue with a 3px `rgba(37,99,235,0.15)` ring (`box-shadow`, the world's one state use); the default outline is suppressed on the box.
- **[Swiss Minimal Light]** **Error:** 12.5px Danger Red under the field, paired with `aria-invalid`; the border itself is not repainted.
- **[Swiss Minimal Light]** **Details:** number inputs suppress the native spinner (`appearance: textfield`, webkit spin buttons hidden); autofill repaints Working White with ink text; the select keeps its native arrow; the file input renders a 6px-radius Row Slate chip with 13px Control Slate text. The Income/Expense type picker is two segmented boxes: peer-checked fills Blue Tint with a #2563EB border and a #1D4ED8 600 label, keyboard focus keeps the 2px blue outline.

### Cards / Sheets
- **[Swiss Minimal Light]** **`sheet`:** Working White, 1px Frame Slate, 12px radius, clipped (`overflow: hidden`). Cap `14px 20px` over a Row Slate rule — 15px/600 title left, 13px tabular Secondary Slate meta right; body padding 20px; foot `12px 20px`, 12.5px Secondary Slate, over the inner rule.
- **[Swiss Minimal Light]** **`figure-card`:** Panel Slate fill (#F8FAFC), 1px Frame Slate, 12px radius, 20px padding; its label → figure → note stack is the unit of the summary row.

### Specimen Figure / Specimen Ledger (`figure` pattern, Swiss Light, signature)
The front page's proof component: white (`bg-white`) with 1px hairline border (`border #E4E4E2`), zero radius. A figcaption strip with baseline-justified small-labels left/right, then rows (label at 14px/500 vs amount at 14px `.tnum` in Info Gray), each divided by #EFEFED, a 4px meter per row, and a 12.5px footer note above a full border-t stating the currency (MYR) once, never repeated per row. The special row, the budget overshoot, is the only place a second state exists: the amount in 600 Fortuna Red, the meter (`meter-over`) driving the full-width accent fill, and a 12.5px explanatory note.
- **Background:** Sheet White (#FFFFFF) on Paper Ground.
- **Border:** 1px Hairline; internal dividers 1px Row Hairline.
- **Internal Padding:** 20px horizontal (px-5); row vertical rhythm 14px (py-3.5); figcaption 16/12; footer 12/12.
- **Focus/hover:** none; the figure is static by design; its honesty is the interaction.

### Specimen Sheet (auth, Swiss Light, signature)
The signed-out page as the account's first sheet. White (#FFFFFF) with a 1px Hairline frame, zero radius, 480px wide at desktop (a `max-w-[528px]` column including 24px gutters; full-width-minus-24px-gutters on mobile), centred on the paper ground at `pt-16 md:pt-24`.
- **Caption strip:** baseline-justified small-labels ("Fortuna" | page tag), `16px 24px 12px`, closing hairline.
- **Sheet body:** `24px` sides: Sheet Title, one-line deck (15px/1.7 Info Gray), then the field rows.
- **Submit:** full-bleed accent; **footnote:** `12px 24px`, 12.5px/1.6 Info Gray over a hairline.
- **Under the sheet:** one quiet cross-link (`mt-5`, 15px/500): log in ↔ create account ↔ reset; the verify page carries log-out there.
- **States:** errors land inside their field row (accent 12.5px), statuses sit under the deck; nothing else moves, nothing floats.

### Meter
- **[Swiss Light]** A 4px data bar: full-width Meter Track (#F0F0EE) with an ink `span` sizing to the budget share (set by inline `width:`), and the `meter-over` variant filling accent Red. This is how "am I over" reads at a glance, and it pairs with `.tnum` figures set to the same baseline row.
- **[Swiss Minimal Light]** A 6px pill: Row Slate track (#F1F5F9), Ledger Blue fill, 999px radius; `meter-over` fills Danger Red. Every budget row carries `role="img"` with a percent `aria-label` ("Dining: 113 percent of budget, over"), and the reports category sheets reuse the same pill for each category's share of the year (same `role="img"` labeling). The fill is capped at 100% even when the overshoot is more — the red and the note carry the excess, never a bar that breaks its track.

### Figure Cards (Swiss Minimal Light, signature)
The app's answer, in four parts: a Panel Slate card, a metric name in the 11px uppercase label, the value in the 26px/600 tabular figure register with a muted 13px `MYR` prefix, and a 13px Secondary Slate note that states the horizon ("Income minus expenses, all time." / "Everything recorded in October."). Red only appears past the line: a negative total balance or a negative budget remaining flips the figure (and the note's figure) to Danger Red; the reports page reuses the row for the year's four answers ("Total income", "Total spent", "Net saved", "Savings rate"), where a negative net or rate flips the same way and a year with no income shows the rate as an em dash with a plain note ("No income to rate in {year} yet."). A missing budget renders an em dash in Secondary Slate with a quiet link to set one. Label → figure → note is fixed order; currency is stated in the prefix, never repeated beside the number.

### Budget Check Sheet (Swiss Minimal Light, signature)
The hero, now in the working register: a white sheet titled "This month's budgets" with the month as tabular meta, rows of name (14px/500) · spent/limit (14px `.tnum`, Secondary Slate; the "/" in Faint Slate, spaced) · the 6px meter. Past the line: the amount turns 600 Danger Red, the meter turns red, and a 13px note reads "Over budget. **X** past the line." with the excess tabular. The foot states "Amounts in MYR." plus the count of lines past budget ("3 lines past their budget." / "All lines are within budget.") and a quiet "Manage budgets" link. The same rows render on the Budgets page under three month figures (budgeted, spent, left).

### The Ruled Register (Swiss Minimal Light, signature)
The reports family's ledger, applied twice: the twelve months of the year, then the ledger's years. A Panel Slate header band of 11px uppercase column labels (month · entries · income · spent · net · rate) sits over 14px rows divided by Row Slate inner rules and closed by the year totals band ("The year") above the sheet foot. Every row is a whole-row anchor, not a link inside the row: hover tints the row Panel Slate and the row opens its detail — a month opens `transactions.index` filtered to its `start_date`/`end_date`, a year opens `reports.index?year=` — with an sr-only line naming the destination. Months without entries keep their line, rendered in Secondary Slate with em dashes, never hidden and never zeroed; income figures carry Growth Green with the "+" prefix, a negative net flips to Danger Red, and the rate column rides at 13px Secondary Slate. In "The years", the selected year's row carries Blue Tint with a Ledger Blue Press label and `aria-current="true"`. Below `sm` each row stacks: month · entry count beside the net, with a 12.5px income / spent / rate line under it.

### Navigation
- **[Swiss Light]** **Header (welcome + auth):** the Fortuna wordmark (`wordmark`: 700, 1.5rem, −0.03em, leading-none), an anchor to `/`. Right: small-label text links (`Log in`, fading to 60% opacity on hover and hidden below sm) and the small-variant primary button (`Create account`). The authenticated state replaces the auth cluster with a small-label `Dashboard` link (its hover is the one place nav takes the accent). The two accent invitations never appear next to each other by design. The auth header never carries the accent button; the sheet's submit is the page's only red. The top bar bottoms with a 2px ink rule — the page's only double-weight border bottom.
- **[Swiss Minimal Light]** **Top bar:** the `wordmark` at 1.25rem, 700, −0.02em; right side: the current date in the 11px uppercase label, tabular, Secondary Slate (`md+`) and a `Menu`/`Close` text button below `lg` (the same label treatment, hover to Slate Ink).
- **[Swiss Minimal Light]** **Sidebar:** 240px white rail with a 1px Frame Slate right rule, sticky under the bar; the list reads Dashboard · Transactions · Budgets · Categories · Reports (Categories sits with the budgets it feeds, the year's look-back closes); nav links are 8px-radius rows (14px/500, `px-3 py-2.5`): at rest Secondary Slate on white; hover Panel Slate fill with Slate Ink text; active (and only active) Blue Tint fill with Ledger Blue Press text and `aria-current="page"`. The identity foot sits under a Row Slate rule: a 32px monogram chip (Panel Slate, Frame Slate border, 8px radius, 12px/600 Control Slate initial), the user's name at 13px/500, then 12px/500 Profile and Log out actions beside it. On mobile the identical nav and identity block move into a full-screen white menu under the top bar.

### Dialog Sheets (Swiss Minimal Light)
Every app form is a dialog sheet: the `sheet` pattern at `max-w-[480px]`, centred at `sm:py-12` inside a fixed wrapper over `bg-[#0F172A]/40` (Slate Ink at 40%), top-aligned on mobile. `role="dialog"` + `aria-modal="true"` + `aria-labelledby` point at the cap title; the cap carries a text Close on the right; the submit is the sheet's block button; a foot line pairs Cancel (quiet link) with the MYR note or a receipt reminder. Behavior ships in `layouts/app.blade.php`: focus moves to the first field on open, Escape closes, Tab is trapped in the open sheet, focus returns to the trigger, and sheets open already-validated when server errors exist (`hidden` class toggle only — no JS framework).

### Charts (Swiss Minimal Light)
ApexCharts; every chart ships `animations: { enabled: false }` (The Flat Stability Rule). **Spending trend:** straight 2px lines — income #94A3B8, expense Ledger Blue #2563EB, net #64748B dashed (5,5); grid #E2E8F0 dashed 4; axis and legend text 12px Inter, labels Secondary Slate, legend labels Control Slate with 6px circular markers; points hidden until hover (4px); tooltips light, 12px, money formatted. A year select in the cap (tabular, boxed, 6px radius) reloads the series; the loading note stays until real series land, and a failed fetch replaces the chart with a plain 13px line. **The year in months (reports):** the same palette as columns — income columns #94A3B8 and spend columns Ledger Blue #2563EB at 55% width, the 2px net line #64748B dashed over them — over the same grid treatment (dashed 4), 12px Secondary Slate axis text, points hidden until a 4px hover, toolbar and zoom off, light 12px money tooltips; a three-item legend row (Income dot #94A3B8, Spend dot #2563EB, Net dash #64748B, 12px Control Slate) sits above the plot. The sheet carries authored states: a 13px loading note until the `/api/reports/monthly` series land, a plain 13px line when the fetch fails, and, for a year with no entries, no canvas at all — an empty note with a "Record an entry" quiet link. **Where it went:** a 66% donut in the user's own category colors with 2px white strokes; the center total is 22px/600 Slate Ink over a 12px Secondary Slate label; the legend sits beneath, centered; empty data swaps the chart for a 14px line about the first expense.

### Notices (Swiss Minimal Light)
Tinted state strips, not cards: 10px radius, 1px border, `12px 16px`, 13.5px, leading with an 11px uppercase flag (11px `small-label`: "Noted" / "Error"). Success: #F0FDF4 ground, #BBF7D0 border, #166534 text. Error: #FEF2F2 ground, #FECACA border, #B91C1C text. One-line content wraps rather than truncates; the tint is the message, never decoration.

### Exported Documents (Swiss Minimal Light)
The exports are quiet actions in the inline register, and the documents they produce keep the world's palette and honesty.
- **Transaction history (CSV)** — `GET /transactions/export`, offered in the Records cap beside the entry count (hidden when there are no records). It streams exactly what the filter shows: the page's whole filter set applies (`search` over descriptions, `type`, `category_id`, `start_date`, `end_date`, `amount_min`, `amount_max`) and the rows keep the chosen order (`newest` / `oldest` / `highest` / `lowest`); dates are ISO (`2026-10-12`), amounts are two-decimal, ungrouped (`3200.00`), the unit is stated once in the header (`Amount (MYR)`), space- or comma-bearing fields are quoted per RFC 4180, and a UTF-8 BOM leads the file for spreadsheet apps.
- **Monthly summary (PDF)** — `GET /reports/monthly-summary`, offered as a quiet action in the dashboard head. An A4 dompdf document: the figure band (income / spent / net / budget remaining — red only past the line, an em dash when no budgets exist), "This month's budgets" with capped 6px meters (blue, red past the line), "Where it went" as a share table, and a foot stating the currency once and the generation timestamp. It is set in DejaVu Sans — dompdf cannot embed the app's woff2 subsets, so this is the one recorded typeface divergence — with the app's palette and no new colors; numeric columns are right-aligned in place of tabular numerals, tables replace flex/grid, and tables avoid breaking mid-row (`page-break-inside: avoid`) so a page break never cuts a header.

### The Section Rule Pattern (Swiss Light, recurring, not a card)
A `section-head` always sits under a full-width `border-t border-[#111111]` (1px, ink) with pt-10/pt-14 of headroom. This "setting rule" is how a section announces itself: a heavier register against the delicate footer/header hairlines, applied identically in both the feature band and the closing band.

## Do's and Don'ts

### Do:
- **Do** set every money figure with `.tnum` (tabular numerals) in both worlds, so ledger columns align.
- **Do** keep each surface in exactly one world (The Two-World Rule): welcome and auth extend Swiss Light; every signed-in page extends Swiss Minimal Light.
- **Do** ration the accent per world: Fortuna Red only for response positions in Swiss Light; one blue thread (#2563EB) per region in the app, with green and red kept as state flags on data.
- **Do** separate surfaces by drawing them: #EFEFED inside a frame, #E4E4E2 between frames, 1px #111111 above headings, 2px ink under the header (Swiss Light); 1px #E2E8F0 frames and #F1F5F9 inner rows (app). That's the whole hierarchy toolkit in both worlds.
- **Do** keep each world's typographic habits: Archivo's negative tracking (−0.02 to −0.035em) and `text-wrap: balance` on Swiss Light headline classes; Inter with 26px/600 tabular figure cards and one hierarchy step per role in the app.
- **Do** keep the specimen table honest: label it "SPECIMEN" / "Illustrative data"; roadmap items go in the "In later editions" list as notices, never as shipped claims.
- **Do** show absent data as absent, the way the register does: a month with no entries renders em dashes in Secondary Slate — never a row of zeros — and an undefined ratio (no income to rate, no budget set) renders an em dash with a plain note or quiet link, never a number the data doesn't support.
- **Do** respect `prefers-reduced-motion` (both shells ship the kill switch: all transitions disabled) even if future motion is added.
- **Do** build each signed-out page as one specimen sheet on the ground: caption tags, headline, deck, hairline field rows, one full-bleed accent submit, hairline footnote, one quiet cross-link below (The One Sheet Rule), and put each error in the field row it belongs to.
- **Do** make app dialogs modal in behavior as well as looks: `role="dialog"` + `aria-modal="true"`, focus moved to the first field on open, Escape to close, Tab trapped inside, focus returned to the trigger — the shipped `layouts/app.blade.php` script is the contract.
- **Do** cap the app meter fill at 100%: past the line, the red amount and the "past the line" note carry the excess, not a bar that overruns its track.

### Don't:
- **Don't** introduce `box-shadow`, `backdrop-filter`, gradient panels, or glass as a visible surface treatment anywhere; flat-with-frames is the committed vocabulary of both worlds, not an early-stage limitation. The only sanctioned `box-shadow` uses in the CSS are state and plumbing: `box-shadow: none` suppressions (both worlds), Chrome's autofill white-inset repaint (both worlds), and the app field's 3px `rgba(37,99,235,0.15)` focus ring — a focus state, never elevation.
- **Don't** round corners in the Swiss Light world: 0px radius everywhere; the scrollbar thumb's 8px is chrome-only. And don't sharpen the app world either — 8px controls and 12px surfaces are its language. Geometry never crosses worlds (The Two-World Rule).
- **Don't** add entrance or scroll animations to any surface: the first painted frame is the final state in both worlds, and the app's chart animations stay disabled (The Flat Stability Rule).
- **Don't** use an accent on gray text, resting numerals, or decoration — in either world. Scarcity is what makes the one blue thread and the red flag read.
- **Don't** fabricate proof: no testimonials, no invented user counts, no benchmarks or guarantees; the specimen ledger table is the proof component.
- **Don't** add a second type family to a world: Archivo carries Swiss Light, Inter carries the app; data legibility comes from `.tnum`, not a mono face.
- **Don't** mix worlds on one page — no blue in the auth sheet, no Fortuna Red in the app, no rounded 12px cards in the report, no 0px corners in the app.
- **Don't** inherit the stock Breeze leftovers (`x-header`, `x-modal`, `x-nav-link`, the dropdowns, the starter buttons/inputs, `layouts/navigation.blade.php`): referenced by no view, styled by an older starter, scaffolding rather than precedent.
- **Don't** let semantic colors decorate the app: green marks income, red marks overshoots and errors, and neither marks anything else.
