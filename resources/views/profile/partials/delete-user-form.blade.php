<section class="mt-8" aria-label="Delete account">
    <div class="sheet">
        <div class="sheet-cap">
            <h2 class="text-[15px] font-semibold">Delete account</h2>
        </div>

        <div class="sheet-body">
            <p class="sheet-deck">Once your account is deleted, every record, receipt, and budget goes with it.</p>
        </div>

        <button type="button"
                onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                class="btn-delete-block">
            {{ __('Delete account') }}
        </button>

        <p class="sheet-foot">Deletion is immediate and permanent.</p>

        {{-- Delete account confirmation sheet --}}
        <div id="delete-account-modal"
             role="dialog" aria-modal="true" aria-labelledby="delete-account-modal-title"
             class="{{ $errors->userDeletion->isNotEmpty() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-[#0F172A]/40">
            <div class="flex min-h-full items-start justify-center p-4 sm:py-12">
                <div class="sheet w-full max-w-[480px]">
                    <div class="sheet-cap">
                        <h2 id="delete-account-modal-title" class="text-[15px] font-semibold">Confirm deletion</h2>
                        <button type="button" onclick="document.getElementById('delete-account-modal').classList.add('hidden')" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Close</button>
                    </div>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="sheet-body">
                            <p class="sheet-deck">Enter your password to confirm. Everything filed under this account is deleted with it.</p>

                            <div class="mt-5">
                                <div class="field">
                                    <label for="delete_account_password" class="field-label">{{ __('Password') }}</label>
                                    <input id="delete_account_password" name="password" type="password" autocomplete="current-password" class="field-input">
                                    @error('password', 'userDeletion')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-danger-block">{{ __('Delete account') }}</button>

                        <div class="sheet-foot flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                            <button type="button" onclick="document.getElementById('delete-account-modal').classList.add('hidden')" class="quiet-link text-[12.5px] font-medium">
                                {{ __('Cancel') }}
                            </button>
                            <span>This cannot be undone.</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
