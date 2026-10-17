<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

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
            /* Fortuna app pages: Inter, self-hosted latin subsets */
            @font-face { font-family: 'Inter'; src: url('/fonts/inter-400.woff2') format('woff2'); font-weight: 400; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Inter'; src: url('/fonts/inter-500.woff2') format('woff2'); font-weight: 500; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Inter'; src: url('/fonts/inter-600.woff2') format('woff2'); font-weight: 600; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Inter'; src: url('/fonts/inter-700.woff2') format('woff2'); font-weight: 700; font-style: normal; font-display: swap; }

            :root {
                --ground: #FFFFFF;
                --surface: #F8FAFC;
                --surface-strong: #F1F5F9;
                --ink: #0F172A;
                --ink-mid: #64748B;
                --ink-faint: #94A3B8;
                --rule: #E2E8F0;
                --rule-inner: #F1F5F9;
                --primary: #2563EB;
                --primary-press: #1D4ED8;
                --primary-tint: #EFF6FF;
                --success: #16A34A;
                --success-text: #15803D;
                --danger: #DC2626;
            }

            html, body { background: var(--ground); }
            body {
                font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: var(--ink);
                font-size: 16px;
                line-height: 1.5;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            ::selection { background: #BFDBFE; color: var(--ink); }
            ::-moz-selection { background: #BFDBFE; color: var(--ink); }
            ::-webkit-scrollbar { width: 12px; height: 12px; }
            ::-webkit-scrollbar-track { background: var(--ground); }
            ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 999px; border: 3px solid var(--ground); }
            ::-webkit-scrollbar-thumb:hover { background: var(--ink-faint); }

            input, select, textarea { caret-color: var(--primary); }

            a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible, summary:focus-visible, [tabindex]:focus-visible {
                outline: 2px solid var(--primary);
                outline-offset: 2px;
            }

            .wordmark { font-weight: 700; letter-spacing: -0.02em; }
            .small-label { font-size: 11px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }
            .tnum { font-variant-numeric: tabular-nums; }

            .quiet-link {
                color: var(--ink-mid);
                font-weight: 500;
                text-decoration: underline;
                text-decoration-thickness: 1px;
                text-decoration-color: #CBD5E1;
                text-underline-offset: 3px;
                transition: color 140ms ease-out, text-decoration-color 140ms ease-out;
            }
            .quiet-link:hover { color: var(--ink); text-decoration-color: var(--ink-faint); }

            .btn-primary {
                display: inline-block;
                border: 0;
                border-radius: 8px;
                background: var(--primary);
                color: #FFFFFF;
                font-family: inherit;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.5;
                padding: 10px 16px;
                text-align: center;
                white-space: nowrap;
                cursor: pointer;
                transition: background-color 140ms ease-out;
            }
            .btn-primary:hover { background: var(--primary-press); }

            .btn-block, .btn-danger-block, .btn-delete-block {
                display: block;
                width: calc(100% - 40px);
                margin: 0 20px 20px;
                border: 0;
                border-radius: 8px;
                background: var(--primary);
                color: #FFFFFF;
                font-family: inherit;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.5;
                padding: 12px 0;
                text-align: center;
                cursor: pointer;
                transition: background-color 140ms ease-out, border-color 140ms ease-out, color 140ms ease-out;
            }
            .btn-block:hover { background: var(--primary-press); }
            .btn-danger-block { background: var(--danger); }
            .btn-danger-block:hover { background: #B91C1C; }
            .btn-delete-block { border: 1px solid var(--rule); background: #FFFFFF; color: var(--ink); padding: 11px 0; }
            .btn-delete-block:hover { border-color: #FECACA; background: #FEF2F2; color: var(--danger); }

            .sheet { background: #FFFFFF; border: 1px solid var(--rule); border-radius: 12px; overflow: hidden; }
            .sheet-cap {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 14px 20px;
                border-bottom: 1px solid var(--rule-inner);
            }
            .sheet-title {
                font-weight: 600;
                font-size: 1.5rem;
                line-height: 1.25;
                letter-spacing: -0.02em;
            }
            .sheet-deck { font-size: 14px; line-height: 1.6; color: var(--ink-mid); }
            .sheet-body { padding: 20px; }
            .sheet-foot {
                padding: 12px 20px;
                border-top: 1px solid var(--rule-inner);
                font-size: 12.5px;
                line-height: 1.6;
                color: var(--ink-mid);
            }

            .figure-card { background: var(--surface); border: 1px solid var(--rule); border-radius: 12px; padding: 20px; }
            .figure-value { margin-top: 10px; font-size: 26px; font-weight: 600; letter-spacing: -0.01em; line-height: 1; }
            .figure-note { margin-top: 8px; font-size: 13px; line-height: 1.5; color: var(--ink-mid); }

            .field { }
            .field:not(:last-child) { margin-bottom: 16px; }
            .field-label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: #334155; }
            .field-input {
                display: block;
                width: 100%;
                border: 1px solid var(--rule);
                border-radius: 8px;
                background-color: #FFFFFF;
                padding: 9px 12px;
                font-family: inherit;
                font-size: 14px;
                line-height: 1.5;
                color: var(--ink);
                transition: border-color 140ms ease-out, box-shadow 140ms ease-out;
            }
            .field-input::placeholder { color: #64748B; }
            .field-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
            .field-input[type="number"] { appearance: textfield; -moz-appearance: textfield; }
            .field-input[type="number"]::-webkit-outer-spin-button,
            .field-input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
            select.field-input { cursor: pointer; }
            .field-input:-webkit-autofill,
            .field-input:-webkit-autofill:hover,
            .field-input:-webkit-autofill:focus {
                -webkit-box-shadow: 0 0 0 1000px #FFFFFF inset;
                -webkit-text-fill-color: var(--ink);
                caret-color: var(--ink);
            }
            .field-error { margin-top: 6px; font-size: 12.5px; line-height: 1.5; color: var(--danger); }

            .check-input { width: 16px; height: 16px; flex: none; accent-color: var(--primary); cursor: pointer; }

            /* Touch-sized controls: 16px inputs stop iOS Safari from zooming the page on focus. */
            @media (max-width: 639px) {
                .field-input { font-size: 16px; }
            }

            .notice {
                display: flex;
                flex-wrap: wrap;
                align-items: baseline;
                gap: 2px 16px;
                border: 1px solid;
                border-radius: 10px;
                padding: 12px 16px;
                font-size: 13.5px;
                line-height: 1.6;
            }
            .notice-success { background: #F0FDF4; border-color: #BBF7D0; color: #166534; }
            .notice-error { background: #FEF2F2; border-color: #FECACA; color: #B91C1C; }

            .meter { height: 6px; border-radius: 999px; background: var(--surface-strong); overflow: hidden; }
            .meter > span { display: block; height: 100%; border-radius: 999px; background: var(--primary); }
            .meter-over > span { background: var(--danger); }

            @media (prefers-reduced-motion: reduce) {
                * { transition: none !important; }
            }
        </style>
    </head>
    <body class="min-h-screen">
        <a href="#app-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:bg-[#0F172A] focus:text-white focus:px-3 focus:py-2 text-sm">Skip to content</a>

        {{-- Top bar --}}
        <header class="sticky top-0 z-40 bg-white border-b border-[#E2E8F0]">
            <div class="h-16 px-6 flex items-center justify-between">
                <a href="/" class="wordmark text-[1.25rem] leading-none">Fortuna</a>
                <div class="flex items-center gap-6">
                    <span class="small-label tnum text-[#64748B] hidden md:inline">{{ now()->format('M j, Y') }}</span>
                    <button type="button" id="menu-toggle" aria-expanded="false" aria-controls="app-menu"
                            class="small-label text-[#64748B] hover:text-[#0F172A] transition-colors lg:hidden">Menu</button>
                </div>
            </div>
        </header>

        <div class="flex items-stretch">
            <x-sidebar />

            <div class="flex-1 min-w-0 flex flex-col min-h-[calc(100vh-4rem)]">
                <main id="app-content" class="flex-1 w-full">
                    <div class="max-w-6xl mx-auto px-6 py-8 md:py-12">
                        {{ $slot }}
                    </div>
                </main>

                {{-- Footer --}}
                <footer class="border-t border-[#E2E8F0]">
                    <div class="max-w-6xl mx-auto px-6 h-16 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="wordmark text-[0.95rem] leading-none">Fortuna</span>
                        <p class="small-label text-[#64748B]">&copy; {{ date('Y') }} Fortuna · Master Your Money</p>
                    </div>
                </footer>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="app-menu" class="hidden lg:hidden fixed inset-x-0 top-16 bottom-0 z-30 bg-white overflow-y-auto">
            <x-app-nav class="px-3 py-4" />
            <div class="mx-5 mt-2 border-t border-[#F1F5F9] py-5">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center text-[12px] font-semibold text-[#334155] uppercase" aria-hidden="true">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                    <p class="text-[13px] font-medium leading-tight truncate">{{ Auth::user()->name }}</p>
                </div>
                <div class="mt-3 flex items-center gap-4">
                    <a href="{{ route('profile.edit') }}" class="text-[12px] font-medium transition-colors {{ request()->routeIs('profile.*') ? 'text-[#0F172A]' : 'text-[#64748B] hover:text-[#0F172A]' }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="flex">
                        @csrf
                        <button type="submit" class="text-[12px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Log out</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            (function () {
                var toggle = document.getElementById('menu-toggle');
                var menu = document.getElementById('app-menu');

                if (!toggle || !menu) {
                    return;
                }

                toggle.addEventListener('click', function () {
                    var open = menu.classList.toggle('hidden') === false;
                    toggle.textContent = open ? 'Close' : 'Menu';
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            })();
        </script>

        <script>
            // Sheets behave as dialogs: focus moves in on open, Escape closes, Tab stays inside.
            (function () {
                var dialogs = document.querySelectorAll('[aria-modal="true"]');

                if (!dialogs.length) {
                    return;
                }

                var lastTrigger = null;

                function isVisible(dialog) {
                    return !dialog.classList.contains('hidden');
                }

                function visibleDialogs() {
                    return Array.prototype.filter.call(dialogs, isVisible);
                }

                function focusables(dialog) {
                    return dialog.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
                }

                function openDialog(dialog) {
                    var active = document.activeElement;

                    if (active && active !== document.body) {
                        lastTrigger = active;
                    }

                    var target = dialog.querySelector('input:not([type="hidden"]), select, textarea') || focusables(dialog)[0] || dialog;

                    if (target === dialog && !dialog.hasAttribute('tabindex')) {
                        dialog.setAttribute('tabindex', '-1');
                    }

                    target.focus();
                }

                function closeDialog(dialog) {
                    dialog.classList.add('hidden');

                    if (lastTrigger && document.contains(lastTrigger)) {
                        lastTrigger.focus();
                    }

                    lastTrigger = null;
                }

                dialogs.forEach(function (dialog) {
                    new MutationObserver(function () {
                        if (isVisible(dialog)) {
                            openDialog(dialog);
                        } else if (lastTrigger && dialog.contains(document.activeElement)) {
                            lastTrigger.focus();
                            lastTrigger = null;
                        }
                    }).observe(dialog, { attributes: true, attributeFilter: ['class'] });

                    if (isVisible(dialog)) {
                        openDialog(dialog);
                    }
                });

                document.addEventListener('keydown', function (event) {
                    var open = visibleDialogs();

                    if (!open.length) {
                        return;
                    }

                    var dialog = open[open.length - 1];

                    if (event.key === 'Escape') {
                        event.preventDefault();
                        closeDialog(dialog);

                        return;
                    }

                    if (event.key !== 'Tab') {
                        return;
                    }

                    var items = focusables(dialog);

                    if (!items.length) {
                        return;
                    }

                    var first = items[0];
                    var last = items[items.length - 1];

                    if (!dialog.contains(document.activeElement)) {
                        event.preventDefault();
                        first.focus();
                    } else if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                });
            })();
        </script>

        @stack('scripts')
    </body>
</html>
