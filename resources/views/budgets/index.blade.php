<x-app-layout title="Budgets | Fortuna">
    @php
        $monthLabel = preg_match('/^\d{4}-\d{2}$/', $selectedMonth)
            ? \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y')
            : $selectedMonth;
        $totalBudgeted = (float) $budgets->sum('amount');
        $totalSpent = (float) $budgets->sum(fn ($budget) => (float) $budget->spent);
        $totalLeft = $totalBudgeted - $totalSpent;
        $overCount = $budgets->filter(fn ($budget) => (float) $budget->spent > (float) $budget->amount)->count();
    @endphp

    {{-- Page head --}}
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="sheet-title">Budgets</h1>
            <p class="mt-1.5 text-[14px] leading-[1.6] text-[#64748B]">The lines you set against what {{ $monthLabel }} actually spent.</p>
        </div>
        <div class="flex flex-wrap items-end gap-x-6 gap-y-4">
            <form method="GET" action="{{ route('budgets.index') }}">
                <label for="month" class="field-label">Month</label>
                <input type="month" id="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="field-input tnum" style="width: auto;">
            </form>
            <button type="button" onclick="document.getElementById('budget-modal').classList.remove('hidden')" class="btn-primary">Set a budget</button>
        </div>
    </div>

    @if (session('success'))
        <div class="notice notice-success mt-8">
            <span class="small-label">Noted</span>
            <p>{{ session('success') }}</p>
        </div>
    @elseif (session('error'))
        <div class="notice notice-error mt-8">
            <span class="small-label">Error</span>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Month figures --}}
    <section class="mt-8" aria-label="Figures for {{ $monthLabel }}">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Total budgeted</p>
                <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totalBudgeted, 2) }}</p>
                <p class="figure-note">The lines set for {{ $monthLabel }}.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Spent against them</p>
                <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totalSpent, 2) }}</p>
                <p class="figure-note">Expenses inside budgeted categories only.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Left on the lines</p>
                @if ($budgets->isEmpty())
                    <p class="figure-value tnum text-[#64748B]">—</p>
                    <p class="figure-note">Set a line to begin.</p>
                @elseif ($totalLeft < 0)
                    <p class="figure-value tnum text-[#DC2626]"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>-{{ number_format(abs($totalLeft), 2) }}</p>
                    <p class="figure-note">
                        Past the lines by <span class="tnum font-medium text-[#DC2626]">{{ number_format(abs($totalLeft), 2) }}</span>.
                    </p>
                @else
                    <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totalLeft, 2) }}</p>
                    <p class="figure-note">Budgeted minus spent in those categories.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- The month's lines --}}
    <section class="mt-8" aria-label="Budgets for {{ $monthLabel }}">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">The month's lines</h2>
                <span class="text-[13px] tnum text-[#64748B]">{{ $monthLabel }}</span>
            </div>

            @if ($budgets->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-[14px] leading-[1.6] text-[#64748B]">
                        No budgets set for {{ $monthLabel }}. Give each category a line, and the check keeps itself from here.
                    </p>
                    <p class="mt-3">
                        <button type="button" onclick="document.getElementById('budget-modal').classList.remove('hidden')" class="quiet-link text-[14px]">Set the first budget</button>
                    </p>
                </div>
            @else
                <div class="px-5 py-2">
                    @foreach ($budgets as $budget)
                        @php
                            $spent = (float) $budget->spent;
                            $limit = (float) $budget->amount;
                            $over = $spent > $limit;
                            $rawPercent = $limit > 0 ? (int) round($spent / $limit * 100) : 100;
                            $meterPercent = min(100, $rawPercent);
                        @endphp
                        <div class="py-4 {{ $loop->last ? '' : 'border-b border-[#F1F5F9]' }}">
                            <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                <p class="font-medium">{{ $budget->category->name }}</p>
                                <p class="flex items-baseline gap-x-5 whitespace-nowrap">
                                    <span class="tnum {{ $over ? 'font-semibold text-[#DC2626]' : 'text-[#64748B]' }}">
                                        {{ number_format($spent, 2) }} <span class="text-[#94A3B8]" aria-hidden="true">/</span> {{ number_format($limit, 2) }}
                                    </span>
                                    <a href="{{ route('budgets.index', ['month' => $selectedMonth, 'edit' => $budget->id]) }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Edit</a>
                                </p>
                            </div>
                            <div class="meter mt-2.5 {{ $over ? 'meter-over' : '' }}" role="img" aria-label="{{ $budget->category->name }}: {{ $rawPercent }} percent of budget{{ $over ? ', over' : '' }}">
                                <span style="width: {{ $meterPercent }}%"></span>
                            </div>
                            @if ($over)
                                <p class="mt-2 text-[13px] leading-snug text-[#64748B]">
                                    Over budget. <span class="tnum font-medium text-[#DC2626]">{{ number_format($spent - $limit, 2) }}</span> past the line.
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 px-5 py-3 border-t border-[#F1F5F9]">
                    <p class="text-[12.5px] leading-relaxed text-[#64748B]">
                        Amounts in MYR. One budget per category per month.
                        @if ($overCount > 0)
                            {{ $overCount }} {{ Str::plural('line', $overCount) }} past {{ $overCount === 1 ? 'its' : 'their' }} budget.
                        @endif
                    </p>
                    <a href="{{ route('dashboard') }}" class="quiet-link text-[12.5px] whitespace-nowrap">Back to the ledger</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Set / edit budget sheet --}}
    <div id="budget-modal" role="dialog" aria-modal="true" aria-labelledby="budget-modal-title" class="{{ $editing || $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-[#0F172A]/40">
        <div class="flex min-h-full items-start justify-center p-4 sm:py-12">
            <div class="sheet w-full max-w-[480px]">
                <div class="sheet-cap">
                    <h2 id="budget-modal-title" class="text-[15px] font-semibold">{{ $editing ? 'Edit budget' : 'Set budget' }}</h2>
                    <a href="{{ route('budgets.index', ['month' => $selectedMonth]) }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Close</a>
                </div>

                <form method="POST" action="{{ $editing ? route('budgets.update', $editing->id) : route('budgets.store') }}">
                    @csrf
                    @if ($editing)
                        @method('PUT')
                    @endif

                    <div class="sheet-body">
                        <div class="field">
                            <label for="budget-category" class="field-label">Category</label>
                            <select id="budget-category" name="category_id" required class="field-input">
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $editing->category_id ?? null) == $category->id)>
                                        {{ $category->name }} · {{ $category->type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="budget-month" class="field-label">Month</label>
                            <input type="month" id="budget-month" name="month" value="{{ old('month', $editing->month ?? $selectedMonth) }}" required class="field-input tnum">
                            @error('month')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="budget-amount" class="field-label">Budget amount</label>
                            <input type="number" id="budget-amount" name="amount" step="0.01" min="0" placeholder="0.00" required value="{{ old('amount', $editing->amount ?? '') }}" class="field-input tnum">
                            @error('amount')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        @error('budget')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-block">{{ $editing ? 'Save changes' : 'Set the budget' }}</button>

                    <div class="sheet-foot flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                        <a href="{{ route('budgets.index', ['month' => $selectedMonth]) }}" class="quiet-link text-[12.5px]">Cancel</a>
                        <span>Amounts in MYR. One budget per category per month.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
