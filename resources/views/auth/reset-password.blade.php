<x-guest-layout title="Set a new password | Fortuna">
    <div class="w-full max-w-[528px] mx-auto px-6 pt-16 md:pt-24">
        <div class="sheet">
            <div class="sheet-cap">
                <span class="small-label">Fortuna</span>
                <span class="small-label text-[#6E6E6E]">Reset</span>
            </div>
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="sheet-body">
                    <h1 class="sheet-title">Set a new password.</h1>
                    <p class="sheet-deck">Choose the new key for this ledger. Your pages and entries stay exactly where they are.</p>

                    <div class="mt-5">
                        <div class="field">
                            <label for="email" class="small-label field-label">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username" class="field-input" @error('email') aria-describedby="email-error" aria-invalid="true" @enderror>
                            @error('email')
                                <p id="email-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password" class="small-label field-label">Password</label>
                            <input id="password" type="password" name="password" required autofocus autocomplete="new-password" class="field-input" @error('password') aria-describedby="password-error" aria-invalid="true" @enderror>
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
                <button type="submit" class="btn-primary">Save the new password</button>
                <p class="sheet-foot">One ledger, one user. Nothing is shared.</p>
            </form>
        </div>
        <p class="mt-5 text-[15px] font-medium">
            <a href="{{ route('login') }}" class="quiet-link">Back to log in</a>
        </p>
    </div>
</x-guest-layout>
