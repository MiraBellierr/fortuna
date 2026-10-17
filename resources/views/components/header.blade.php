@props(['title'])

<header {{ $attributes->merge(['class' => 'h-16 bg-white dark:bg-[#111111] border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-8 sticky top-0 z-10']) }}>
    <h2 class="text-lg font-semibold">{{ $title }}</h2>
    <div class="flex items-center gap-4">
        {{ $slot }}

        <a href="{{ route('profile.edit') }}" title="{{ __('Profile') }}" aria-label="{{ __('Profile') }}"
           class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase hover:ring-2 hover:ring-[#f53003]/50 transition-all">
            {{ str(Auth::user()?->name ?? '')->substr(0, 1) }}
        </a>
    </div>
</header>
