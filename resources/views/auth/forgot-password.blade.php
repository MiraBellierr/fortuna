<x-guest-layout title="Reset your password | Fortuna">
    <div class="w-full max-w-[528px] mx-auto px-6 pt-16 md:pt-24">
        <div class="sheet">
            <div class="sheet-cap">
                <span class="small-label">Fortuna</span>
                <span class="small-label text-[#6E6E6E]">Reset</span>
            </div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="sheet-body">
                    <h1 class="sheet-title">Reset your password.</h1>
                    <p class="sheet-deck">Tell us the email on the account and Fortuna sends a link to set a new one.</p>

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
                    </div>
                </div>
                <button type="submit" class="btn-primary">Send the reset link</button>
                <p class="sheet-foot">The link works once, and expires within the hour.</p>
            </form>
        </div>
        <p class="mt-5 text-[15px] font-medium">
            <a href="{{ route('login') }}" class="quiet-link">Remembered it? Log in</a>
        </p>
    </div>
</x-guest-layout>
