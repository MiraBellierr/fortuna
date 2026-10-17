<x-app-layout title="Profile | Fortuna">
    {{-- Page head --}}
    <div>
        <h1 class="sheet-title">Profile</h1>
        <p class="mt-1.5 text-[14px] leading-[1.6] text-[#64748B]">Your name, your sign-in, and the account itself.</p>
    </div>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div class="notice notice-success mt-8">
            <span class="small-label">Noted</span>
            <p>{{ __('Your changes have been saved.') }}</p>
        </div>
    @endif

    @include('profile.partials.update-profile-information-form')
    @include('profile.partials.update-password-form')
    @include('profile.partials.delete-user-form')
</x-app-layout>
