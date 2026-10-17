@php
    $links = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
        ['label' => 'Transactions', 'route' => 'transactions.index', 'active' => request()->routeIs('transactions.*')],
        ['label' => 'Budgets', 'route' => 'budgets.index', 'active' => request()->routeIs('budgets.*')],
        ['label' => 'Categories', 'route' => 'categories.index', 'active' => request()->routeIs('categories.*')],
        ['label' => 'Reports', 'route' => 'reports.index', 'active' => request()->routeIs('reports.*')],
    ];
@endphp

<nav {{ $attributes->merge(['class' => '']) }} aria-label="Primary">
    <div class="flex flex-col gap-1">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
               @if ($link['active']) aria-current="page" @endif
               class="flex items-center rounded-lg px-3 py-2.5 text-[14px] font-medium transition-colors {{ $link['active'] ? 'bg-[#EFF6FF] text-[#1D4ED8]' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A]' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</nav>
