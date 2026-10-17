<section class="mt-8" aria-label="Password">
    <div class="sheet">
        <div class="sheet-cap">
            <h2 class="text-[15px] font-semibold">Password</h2>
        </div>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="sheet-body">
                <p class="sheet-deck">Change the password you sign in with.</p>

                <div class="mt-5">
                    <div class="field">
                        <label for="update_password_current_password" class="field-label">{{ __('Current password') }}</label>
                        <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="field-input">
                        @error('current_password', 'updatePassword')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="update_password_password" class="field-label">{{ __('New password') }}</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="field-input">
                        @error('password', 'updatePassword')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="update_password_password_confirmation" class="field-label">{{ __('Confirm new password') }}</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="field-input">
                        @error('password_confirmation', 'updatePassword')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-block">{{ __('Update password') }}</button>

            <p class="sheet-foot">Use a long, random password. The book stays private to you.</p>
        </form>
    </div>
</section>
