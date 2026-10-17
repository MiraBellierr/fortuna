---
version: 1
slug: "resources-views-auth-login-blade-php"
primary_target: "resources/views/auth/login.blade.php"
related_targets: ["resources/views/auth/register.blade.php","resources/views/auth/forgot-password.blade.php","resources/views/auth/reset-password.blade.php","resources/views/auth/confirm-password.blade.php","resources/views/auth/verify-email.blade.php","resources/views/layouts/guest.blade.php"]
---

# Surface brief — auth family (login, register, reset flows)

Scope: the six signed-out/session pages: login, register, forgot-password, reset-password, confirm-password, verify-email, plus the shared shell `layouts/guest`. Visitor mode: Operate.

Audience/job/action: a person opening or returning to their private Fortuna ledger. Job: sign in, create the account, or finish one reset/confirm/verify step. Action: submit the single form; log in ↔ create account as quiet cross-links. Proof/content: product truth only — one ledger, one user; the account is private; claims stay inside the front page's own words ("no plan, no card").

Chosen direction: Swiss Light (world settled by the front page, user-pinned). Composition locked in the surface round: **The Specimen Sheet** — the sign-up module as one white specimen sheet on the paper ground: caption tags, headline inside, hairline field rows, sheet-full-bleed accent submit, hairline footnote; the log-in line sits under the sheet. Approved sketch: `.impeccable/mocks/decision/specimen-sheet.html` (code-led build; the sketch is a fixed first-viewport thumbnail).

Memorable moment: the form you fill is set like the ledger you are opening — caption tags on its top edge, quiet hairline rows, one red commitment at its foot.

Unresolved decisions: none blocking. The reset/confirm/verify pages inherit the same sheet with reduced content.

## Direction contract

THESIS: An auth screen that behaves like the first sheet of your own ledger: one captioned white specimen sheet centred on the paper ground, hairline rows, one red commitment — refusing the rounded card-with-shadow and the split hero.
OWN-WORLD: Paper ground #FAFAF8; sheet white #FFFFFF with a 1px hairline frame and caption tags (small-label, baseline-justified); inner row hairlines #EFEFED; ink #111111 text; info gray #6E6E6E labels; one signal accent #E3000F on the submit, focus, and error flags; Archivo only; sharp corners; no shadows; 68px wordmark bar under a 2px ink rule; 76px hairline footer.
STORY: You are opening — or returning to — a private ledger. The page reads straight, asks only what it needs, and the account's first write is your name.
FIRST VIEWPORT: Wordmark top bar; a 480px specimen sheet centred on the ground at pt-24 — caption tags (Fortuna | page tag), headline inside, one-line deck, hairline-ruled field rows, sheet-full-bleed accent submit, hairline footnote; quiet cross-link under the sheet; footer strip at the page foot.
FORM: The Specimen Sheet — position 1 on the derived list of seven, dealt third and locked by the user in the surface round. seed:084117316e2ed111
FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance.
