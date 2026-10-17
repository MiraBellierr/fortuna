<x-guest-layout title="Create account | Fortuna">
    <div class="w-full max-w-[528px] mx-auto px-6 pt-16 md:pt-24">
        <div class="sheet">
            <div class="sheet-cap">
                <span class="small-label">Fortuna</span>
                <span class="small-label text-[#6E6E6E]">New ledger</span>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="sheet-body">
                    <h1 class="sheet-title">Start your ledger with your name.</h1>
                    <p class="sheet-deck">One honest ledger for one person. Entries, receipts, and budgets under this account alone.</p>

                    <div class="mt-5">
                        <div class="field">
                            <label for="name" class="small-label field-label">Full name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="field-input" @error('name') aria-describedby="name-error" aria-invalid="true" @enderror>
                            @error('name')
                                <p id="name-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="email" class="small-label field-label">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com" class="field-input" @error('email') aria-describedby="email-error" aria-invalid="true" @enderror>
                            @error('email')
                                <p id="email-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password" class="small-label field-label">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" class="field-input" @error('password') aria-describedby="password-error" aria-invalid="true" @enderror>
                            @error('password')
                                <p id="password-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password_confirmation" class="small-label field-label">Confirm password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="field-input">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Create your account</button>
                <p class="sheet-foot">No plan, no card. Nothing is shared.</p>
            </form>
        </div>
        <p class="mt-5 text-[15px] font-medium">
            <a href="{{ route('login') }}" class="quiet-link">Already keeping a ledger? Log in</a>
        </p>
    </div>
</x-guest-layout>
