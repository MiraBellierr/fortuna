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
            /* Fortuna auth pages: Archivo, self-hosted latin subsets */
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-400.woff2') format('woff2'); font-weight: 400; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-500.woff2') format('woff2'); font-weight: 500; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-600.woff2') format('woff2'); font-weight: 600; font-style: normal; font-display: swap; }
            @font-face { font-family: 'Archivo'; src: url('/fonts/archivo-700.woff2') format('woff2'); font-weight: 700; font-style: normal; font-display: swap; }

            :root {
                --ground: #FAFAF8;
                --ink: #111111;
                --ink-mid: #6E6E6E;
                --rule: #E4E4E2;
                --rule-inner: #EFEFED;
                --accent: #E3000F;
                --accent-press: #B8000C;
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

            a:focus-visible, button:focus-visible, input:focus-visible, [tabindex]:focus-visible {
                outline: 2px solid var(--accent);
                outline-offset: 3px;
            }

            .wordmark { font-weight: 700; letter-spacing: -0.03em; }
            .small-label { font-size: 12px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; }

            .quiet-link {
                color: var(--ink);
                text-decoration: underline;
                text-decoration-thickness: 1.5px;
                text-decoration-color: #C9C9C5;
                text-underline-offset: 4px;
                transition: text-decoration-color 140ms ease-out;
            }
            .quiet-link:hover { text-decoration-color: var(--ink); }

            .btn-primary {
                display: block;
                width: 100%;
                border: 0;
                background: var(--accent);
                color: #FFFFFF;
                font-family: inherit;
                font-size: 15px;
                font-weight: 600;
                line-height: 1.6;
                padding: 15px 0;
                text-align: center;
                cursor: pointer;
                transition: background-color 140ms ease-out;
            }
            .btn-primary:hover { background: var(--accent-press); }

            .sheet { background: #FFFFFF; border: 1px solid var(--rule); }
            .sheet-cap {
                display: flex;
                align-items: baseline;
                justify-content: space-between;
                padding: 16px 24px 12px;
                border-bottom: 1px solid var(--rule);
            }
            .sheet-title {
                font-weight: 700;
                font-size: 1.875rem;
                line-height: 1.15;
                letter-spacing: -0.03em;
                text-wrap: balance;
            }
            .sheet-deck { margin-top: 12px; font-size: 15px; line-height: 1.7; color: var(--ink-mid); }
            .sheet-body { padding: 24px 24px 10px; }
            .sheet-foot {
                padding: 12px 24px;
                border-top: 1px solid var(--rule);
                font-size: 12.5px;
                line-height: 1.6;
                color: var(--ink-mid);
            }
            .field { padding: 13px 0 12px; border-bottom: 1px solid var(--rule-inner); }
            .field:last-child { border-bottom: 0; }
            .field-label { display: block; color: var(--ink-mid); }
            .field-input {
                display: block;
                width: 100%;
                border: 0;
                background: transparent;
                padding: 5px 0 1px;
                font-family: inherit;
                font-size: 15px;
                line-height: 1.4;
                color: var(--ink);
            }
            .field-input::placeholder { color: #B0B0AC; }
            .field-input:-webkit-autofill,
            .field-input:-webkit-autofill:hover,
            .field-input:-webkit-autofill:focus {
                -webkit-box-shadow: 0 0 0 1000px #FFFFFF inset;
                -webkit-text-fill-color: var(--ink);
                caret-color: var(--ink);
            }
            .field-input:focus,
            .field-input:focus-visible,
            input[type="checkbox"]:focus,
            input[type="checkbox"]:focus-visible {
                box-shadow: none;
            }
            .field-error { margin-top: 6px; font-size: 12.5px; line-height: 1.5; color: var(--accent); }

            @media (prefers-reduced-motion: reduce) {
                * { transition: none !important; }
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col">
        {{-- Top bar --}}
        <header class="border-b-2 border-b-[#111111]">
            <div class="max-w-6xl mx-auto px-6 h-[68px] flex items-center justify-between">
                <a href="/" class="wordmark text-[1.5rem] leading-none">Fortuna</a>
                @if (Route::has('login'))
                    <div class="flex items-center gap-5 sm:gap-7">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="small-label hover:text-[#E3000F] transition-colors">Dashboard</a>
                        @elseif (Route::is('login'))
                            <a href="{{ route('register') }}" class="small-label hover:opacity-60 transition-opacity">Create account</a>
                        @else
                            <a href="{{ route('login') }}" class="small-label hover:opacity-60 transition-opacity">Log in</a>
                        @endauth
                    </div>
                @endif
            </div>
        </header>

        <main class="flex-1 w-full">
            {{ $slot }}
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
