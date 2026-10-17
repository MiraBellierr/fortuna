<section class="mt-8" aria-label="Profile information">
    <div class="sheet">
        <div class="sheet-cap">
            <h2 class="text-[15px] font-semibold">Profile information</h2>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="sheet-body">
                <p class="sheet-deck">The name on your records, and the address you sign in with.</p>

                <div class="mt-5">
                    <div class="field">
                        <label for="name" class="field-label">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="field-input">
                        @error('name')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email" class="field-label">{{ __('Email address') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="field-input">
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="mt-2 text-[13px] leading-relaxed text-[#64748B]">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="quiet-link text-[13px] font-medium">
                                    {{ __('Send the verification email again') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-[13px] font-medium">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-block">{{ __('Save') }}</button>

            <p class="sheet-foot">Used to sign in and to name your records. No one else sees it.</p>
        </form>
    </div>
</section>
