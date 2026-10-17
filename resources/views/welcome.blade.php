<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fortuna | Master Your Money</title>
        <meta name="description" content="Fortuna records every transaction, files each receipt with its entry, and holds your spending up against the budget you set.">

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss | Styles are built by Vite in development and production */
            </style>
        @endif
        <style>
            /* Fortuna front page: Archivo, self-hosted latin subsets */
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-400.woff2') format('woff2'); font-weight: 400; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-500.woff2') format('woff2'); font-weight: 500; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-600.woff2') format('woff2'); font-weight: 600; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-700.woff2') format('woff2'); font-weight: 700; font-style: normal; font-display: swap; }

            :root {
                --ground: #FAFAF8;
                --ink: #111111;
                --ink-mid: #6E6E6E;
                --rule: #E4E4E2;
                --accent: #E3000F;
                --accent-press: #B8000C;
                --track: #F0F0EE;
            }

            html, body { background: var(--ground); }
            body {
                font-family: 'Archivo', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: var(--ink);
                font-size: 16px;
                line-height: 1.6;
            }

            ::selection { background: var(--accent); color: #FFFFFF; }
            ::-moz-selection { background: var(--accent); color: #FFFFFF; }
            ::-webkit-scrollbar { width: 11px; }
            ::-webkit-scrollbar-track { background: var(--ground); }
            ::-webkit-scrollbar-thumb { background: #D6D6D2; border-radius: 8px; border: 3px solid var(--ground); }

            a:focus-visible, button:focus-visible, [tabindex]:focus-visible {
                outline: 2px solid var(--accent);
                outline-offset: 3px;
            }

            .wordmark {
                font-weight: 700;
                letter-spacing: -0.03em;
            }
            .small-label {
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }
            .display-headline {
                font-weight: 700;
                font-size: clamp(2.7rem, 5.4vw, 4.4rem);
                line-height: 1.03;
                letter-spacing: -0.035em;
                text-wrap: balance;
            }
            .section-head {
                font-weight: 700;
                font-size: clamp(1.7rem, 3vw, 2.4rem);
                letter-spacing: -0.03em;
                line-height: 1.15;
                text-wrap: balance;
            }
            .story-head {
                font-weight: 600;
                font-size: 1.25rem;
                letter-spacing: -0.02em;
                line-height: 1.3;
            }
            .tnum { font-variant-numeric: tabular-nums; }

            .btn-primary {
                background: var(--accent);
                color: #FFFFFF;
                font-size: 15px;
                font-weight: 600;
                padding: 14px 28px;
                white-space: nowrap;
                transition: background-color 140ms ease-out;
            }
            .btn-primary:hover { background: var(--accent-press); }

            .quiet-link {
                color: var(--ink);
                text-decoration: underline;
                text-decoration-thickness: 1.5px;
                text-decoration-color: #C9C9C5;
                text-underline-offset: 4px;
                transition: text-decoration-color 140ms ease-out;
            }
            .quiet-link:hover { text-decoration-color: var(--ink); }

            .meter {
                height: 4px;
                background: var(--track);
            }
            .meter > span { display: block; height: 4px; background: var(--ink); }
            .meter-over > span { background: var(--accent); }

            @media (prefers-reduced-motion: reduce) {
                * { transition: none !important; }
            }
        </style>
    </head>
    <body class="min-h-screen">
        <a href="#front-page" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:bg-[#111111] focus:text-white focus:px-3 focus:py-2 text-sm">Skip to content</a>

        {{-- Top bar --}}
        <header class="border-b-2 border-b-[#111111]">
            <div class="max-w-6xl mx-auto px-6 h-[68px] flex items-center justify-between">
                <a href="/" class="wordmark text-[1.5rem] leading-none">Fortuna</a>
                <div class="flex items-center gap-5 sm:gap-7">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="small-label hover:text-[#E3000F] transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="small-label hover:opacity-60 transition-opacity hidden sm:inline">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary max-sm:!px-4 max-sm:!py-3">Create account</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main id="front-page">
            {{-- Opening band: headline + actions against specimen ledger --}}
            <section class="max-w-6xl mx-auto px-6 pt-16 md:pt-24 pb-20">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-y-14">
                    <div class="md:col-span-8 md:pr-16">
                        <h1 class="display-headline">
                            An honest account<br class="hidden lg:block"> of your money.
                        </h1>
                        <p class="mt-8 max-w-[58ch] text-[17px] md:text-[18px] leading-[1.7] text-[#6E6E6E]">
                            Fortuna writes down every transaction the moment it happens, files each
                            receipt with its entry, and holds every month of spending up against
                            the budget you set. One ledger, one user. Your records stay yours.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-6">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn-primary">Open your ledger</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn-primary">Create your account</a>
                                    <a href="{{ route('login') }}" class="quiet-link text-[15px] font-medium">Already keeping a ledger? Log in</a>
                                @endauth
                            @endif
                        </div>
                    </div>

                    {{-- Specimen ledger --}}
                    <aside class="md:col-span-4" aria-label="Specimen ledger, illustrative data">
                        <figure class="border border-[#E4E4E2] bg-white">
                            <figcaption class="flex items-baseline justify-between px-5 pt-4 pb-3 border-b border-[#E4E4E2]">
                                <span class="small-label">This month</span>
                                <span class="small-label text-[#6E6E6E]">Specimen</span>
                            </figcaption>

                            <div class="px-5 py-2">
                                <div class="py-3.5 border-b border-[#EFEFED]">
                                    <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                        <p class="font-medium">Groceries</p>
                                        <p class="tnum whitespace-nowrap text-[#6E6E6E]">312.40 <span aria-hidden="true">/</span> 350.00</p>
                                    </div>
                                    <div class="meter mt-2" role="img" aria-label="Groceries: 89 percent of budget">
                                        <span style="width: 89%"></span>
                                    </div>
                                </div>
                                <div class="py-3.5 border-b border-[#EFEFED]">
                                    <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                        <p class="font-medium">Transport</p>
                                        <p class="tnum whitespace-nowrap text-[#6E6E6E]">86.15 <span aria-hidden="true">/</span> 120.00</p>
                                    </div>
                                    <div class="meter mt-2" role="img" aria-label="Transport: 72 percent of budget">
                                        <span style="width: 72%"></span>
                                    </div>
                                </div>
                                <div class="py-3.5 border-b border-[#EFEFED]">
                                    <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                        <p class="font-medium">Dining &amp; coffee</p>
                                        <p class="tnum whitespace-nowrap font-semibold text-[#E3000F]">168.90 <span aria-hidden="true">/</span> 150.00</p>
                                    </div>
                                    <div class="meter meter-over mt-2" role="img" aria-label="Dining and coffee: 113 percent of budget, over">
                                        <span style="width: 100%"></span>
                                    </div>
                                    <p class="mt-2 text-[12.5px] leading-snug text-[#6E6E6E]">
                                        Over budget. <span class="text-[#E3000F] font-medium">Flagged the day it happened.</span>
                                    </p>
                                </div>
                                <div class="py-3.5">
                                    <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                        <p class="font-medium">Utilities</p>
                                        <p class="tnum whitespace-nowrap text-[#6E6E6E]">74.50 <span aria-hidden="true">/</span> 90.00</p>
                                    </div>
                                    <div class="meter mt-2" role="img" aria-label="Utilities: 83 percent of budget">
                                        <span style="width: 83%"></span>
                                    </div>
                                </div>
                            </div>

                            <p class="px-5 py-3 border-t border-[#E4E4E2] text-[12.5px] text-[#6E6E6E]">Illustrative data in MYR. Your ledger keeps its own pages.</p>
                        </figure>
                    </aside>
                </div>
            </section>

            {{-- Grid columns: what the product does --}}
            <section class="max-w-6xl mx-auto px-6 pb-24">
                <div class="border-t border-[#111111] pt-10">
                    <h2 class="section-head">What Fortuna does</h2>
                </div>
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3">
                    <div class="md:pr-10 md:border-r border-[#E4E4E2]">
                        <h3 class="story-head">Budgets with a line to hold</h3>
                        <p class="mt-4 text-[15.5px] leading-[1.7] text-[#6E6E6E]">
                            Set a monthly limit per category, in the currency you count in. When a line
                            of spending runs past its budget, Fortuna flags it that day, not at a
                            month-end reveal.
                        </p>
                        <p class="mt-3 text-[15.5px] leading-[1.7] text-[#6E6E6E]">
                            Category colours carry into every chart, so a name always reads the same
                            way across your ledger.
                        </p>
                    </div>
                    <div class="mt-10 md:mt-0 md:px-10 md:border-r border-[#E4E4E2]">
                        <h3 class="story-head">Receipts filed under entries</h3>
                        <p class="mt-4 text-[15.5px] leading-[1.7] text-[#6E6E6E]">
                            Photograph a receipt when you pay and attach it to the transaction in
                            seconds. Each file is stored privately and can be reached only by the
                            account that filed it.
                        </p>
                    </div>
                    <div class="mt-10 md:mt-0 md:pl-10">
                        <h3 class="story-head">Reports that stay legible</h3>
                        <p class="mt-4 text-[15.5px] leading-[1.7] text-[#6E6E6E]">
                            Totals by month and year, spending shares by category, and a dashboard
                            that answers one question at a glance: am I still inside the lines?
                        </p>
                        <p class="mt-3 text-[15.5px] leading-[1.7] text-[#6E6E6E]">
                            Any page of history can be filtered and sorted when the totals deserve
                            a second look.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Closing band: CTA + announced work --}}
            <section class="max-w-6xl mx-auto px-6 pb-28">
                <div class="border-t border-[#111111] pt-14 grid grid-cols-1 md:grid-cols-12 gap-y-12">
                    <div class="md:col-span-8 md:pr-16">
                        <h2 class="section-head">
                            Take up a steadier habit<br class="hidden sm:block"> of honest accounts.
                        </h2>
                        <p class="mt-6 max-w-[52ch] text-[17px] leading-[1.7] text-[#6E6E6E]">
                            Create a free account and start your first page of records this evening:
                            no plan, no card, no dashboards you don't want.
                        </p>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-primary mt-10 inline-block">Back to your ledger</a>
                            @else
                                <div class="mt-10 flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-6">
                                    <a href="{{ route('register') }}" class="btn-primary">Create your account</a>
                                    <a href="{{ route('login') }}" class="quiet-link text-[15px] font-medium">Log in instead</a>
                                </div>
                            @endauth
                        @endif
                    </div>
                    <div class="md:col-span-4 md:pl-2">
                        <h3 class="small-label border-b border-[#E4E4E2] pb-3">In later editions</h3>
                        <ul class="mt-5 space-y-3 text-[14.5px] text-[#6E6E6E] list-disc pl-5 marker:text-[#C9C9C5]">
                            <li>Transaction history as CSV</li>
                            <li>A monthly summary as printable PDF</li>
                            <li>A one-tap entry page for the phone</li>
                        </ul>
                        <p class="mt-5 text-[12.5px] text-[#6E6E6E]">Announced because they are coming. Nothing here costs anything yet.</p>
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-[#E4E4E2]">
            <div class="max-w-6xl mx-auto px-6 h-[76px] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="wordmark text-[1.1rem] leading-none">Fortuna</span>
                <p class="small-label text-[#6E6E6E]">&copy; {{ date('Y') }} Fortuna · Master Your Money</p>
            </div>
        </footer>
    </body>
</html>
