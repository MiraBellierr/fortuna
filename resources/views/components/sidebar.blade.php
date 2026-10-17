@props([])

<aside class="hidden lg:flex flex-col w-60 shrink-0 bg-white border-r border-[#E2E8F0] sticky top-16 h-[calc(100vh-4rem)]">
    <x-app-nav class="flex-1 px-3 py-4" />

    <div class="border-t border-[#F1F5F9] px-5 py-4">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center text-[12px] font-semibold text-[#334155] uppercase" aria-hidden="true">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
            <p class="text-[13px] font-medium leading-tight truncate">{{ Auth::user()->name }}</p>
        </div>
        <div class="mt-3 flex items-center gap-4">
            <a href="{{ route('profile.edit') }}" class="text-[12px] font-medium transition-colors {{ request()->routeIs('profile.*') ? 'text-[#0F172A]' : 'text-[#64748B] hover:text-[#0F172A]' }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="flex">
                @csrf
                <button type="submit" class="text-[12px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Log out</button>
            </form>
        </div>
    </div>
</aside>
