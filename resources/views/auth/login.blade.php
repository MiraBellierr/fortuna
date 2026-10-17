<x-guest-layout title="Log in | Fortuna">
    <div class="w-full max-w-[528px] mx-auto px-6 pt-16 md:pt-24">
        <div class="sheet">
            <div class="sheet-cap">
                <span class="small-label">Fortuna</span>
                <span class="small-label text-[#6E6E6E]">Sign in</span>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="sheet-body">
                    <h1 class="sheet-title">Your ledger is as you left it.</h1>
                    <p class="sheet-deck">Sign in and the book opens where you left it. Every entry, receipt, and budget stays as you filed it.</p>

                    @if (session('status'))
                        <p class="mt-4 text-[13.5px] leading-relaxed">{{ session('status') }}</p>
                    @endif

                    <div class="mt-5">
                        <div class="field">
                            <label for="email" class="small-label field-label">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" class="field-input" @error('email') aria-describedby="email-error" aria-invalid="true" @enderror>
                            @error('email')
                                <p id="email-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <div class="flex items-baseline justify-between gap-4">
                                <label for="password" class="small-label field-label">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="quiet-link text-[12.5px] font-medium">Forgot password?</a>
                                @endif
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="field-input" @error('password') aria-describedby="password-error" aria-invalid="true" @enderror>
                            @error('password')
                                <p id="password-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="remember" class="h-4 w-4 rounded-none border-[#C9C9C5] text-[#E3000F]">
                                <span class="text-[14px] leading-none text-[#6E6E6E]">Remember me</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Log in</button>
                <p class="sheet-foot">One ledger, one user. Nothing is shared.</p>
            </form>
        </div>
        <p class="mt-5 text-[15px] font-medium">
            <a href="{{ route('register') }}" class="quiet-link">Not keeping a ledger yet? Create one</a>
        </p>
    </div>
</x-guest-layout>
