<x-guest-layout title="Confirm your password | Fortuna">
    <div class="w-full max-w-[528px] mx-auto px-6 pt-16 md:pt-24">
        <div class="sheet">
            <div class="sheet-cap">
                <span class="small-label">Fortuna</span>
                <span class="small-label text-[#6E6E6E]">Secure</span>
            </div>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="sheet-body">
                    <h1 class="sheet-title">Confirm it's you.</h1>
                    <p class="sheet-deck">This page keeps the ledger private. Enter your password to continue.</p>

                    <div class="mt-5">
                        <div class="field">
                            <label for="password" class="small-label field-label">Password</label>
                            <input id="password" type="password" name="password" required autofocus autocomplete="current-password" class="field-input" @error('password') aria-describedby="password-error" aria-invalid="true" @enderror>
                            @error('password')
                                <p id="password-error" class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Confirm</button>
                <p class="sheet-foot">One ledger, one user. Nothing is shared.</p>
            </form>
        </div>
    </div>
</x-guest-layout>
