<x-app-layout title="Transactions | Fortuna">
    {{-- Page head --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <h1 class="sheet-title">Transaction history</h1>
            <p class="mt-1.5 text-[14px] leading-[1.6] text-[#64748B]">
                @if ($activeFilters)
                    @if (isset($activeFilters['search']))
                        {{ $transactions->total() }} {{ Str::plural('record', $transactions->total()) }} matching "{{ $activeFilters['search'] }}".
                    @else
                        {{ $transactions->total() }} {{ Str::plural('record', $transactions->total()) }} matching these filters.
                    @endif
                @else
                    {{ $transactions->total() }} {{ Str::plural('record', $transactions->total()) }}, newest first.
                @endif
            </p>
        </div>
        <button type="button" onclick="document.getElementById('add-modal').classList.remove('hidden')" class="btn-primary w-full sm:w-auto">Record a transaction</button>
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

    {{-- Filters --}}
    <section class="mt-8" aria-label="Filters">
        <div class="sheet">
            <div class="sheet-body">
                <form method="GET" action="{{ route('transactions.index') }}">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="sm:col-span-2">
                            <label for="filter-search" class="field-label">Search</label>
                            <input type="search" id="filter-search" name="search" value="{{ $activeFilters['search'] ?? '' }}" placeholder="Search descriptions" class="field-input">
                        </div>
                        <div>
                            <label for="filter-type" class="field-label">Type</label>
                            <select id="filter-type" name="type" class="field-input">
                                <option value="">All types</option>
                                <option value="Income" @selected(($activeFilters['type'] ?? null) === 'Income')>Income</option>
                                <option value="Expense" @selected(($activeFilters['type'] ?? null) === 'Expense')>Expense</option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-category" class="field-label">Category</label>
                            <select id="filter-category" name="category_id" class="field-input">
                                <option value="">All categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(($activeFilters['category_id'] ?? null) === (string) $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <details class="mt-4" @if ($advancedFiltersActive) open @endif>
                        <summary class="inline-block cursor-pointer text-[13px] font-medium text-[#64748B] transition-colors hover:text-[#0F172A]">More filters{{ $advancedCount > 0 ? ' · '.$advancedCount.' active' : '' }}</summary>
                        <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <label for="filter-start" class="field-label">From date</label>
                                <input type="date" id="filter-start" name="start_date" value="{{ $activeFilters['start_date'] ?? '' }}" class="field-input tnum">
                            </div>
                            <div>
                                <label for="filter-end" class="field-label">To date</label>
                                <input type="date" id="filter-end" name="end_date" value="{{ $activeFilters['end_date'] ?? '' }}" class="field-input tnum">
                            </div>
                            <div>
                                <label for="filter-amount-min" class="field-label">Minimum amount</label>
                                <input type="number" id="filter-amount-min" name="amount_min" step="0.01" min="0" inputmode="decimal" placeholder="0.00" value="{{ $activeFilters['amount_min'] ?? '' }}" class="field-input tnum">
                            </div>
                            <div>
                                <label for="filter-amount-max" class="field-label">Maximum amount</label>
                                <input type="number" id="filter-amount-max" name="amount_max" step="0.01" min="0" inputmode="decimal" placeholder="0.00" value="{{ $activeFilters['amount_max'] ?? '' }}" class="field-input tnum">
                            </div>
                        </div>
                    </details>

                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div class="sm:w-56">
                            <label for="filter-sort" class="field-label">Order</label>
                            <select id="filter-sort" name="sort" class="field-input">
                                <option value="newest" @selected($sort === 'newest')>Newest first</option>
                                <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                                <option value="highest" @selected($sort === 'highest')>Highest amount</option>
                                <option value="lowest" @selected($sort === 'lowest')>Lowest amount</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-5">
                            <button type="submit" class="btn-primary">Apply</button>
                            @if ($activeFilters)
                                <a href="{{ route('transactions.index') }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <p class="sheet-foot">Search matches descriptions. Amounts in MYR.</p>
        </div>
    </section>

    {{-- Records --}}
    <section class="mt-8" aria-label="Records">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">Records</h2>
                <div class="flex items-baseline gap-4">
                    <span class="text-[13px] tnum text-[#64748B]">{{ $transactions->total() }} {{ Str::plural('entry', $transactions->total()) }}</span>
                    @if ($transactions->total() > 0)
                        <a href="{{ route('transactions.export', $activeFilters) }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Export CSV</a>
                    @endif
                </div>
            </div>

            @if ($transactions->isEmpty())
                <div class="px-5 py-12 text-center">
                    @if ($activeFilters)
                        <p class="text-[14px] leading-[1.6] text-[#64748B]">No records match these filters.</p>
                        <p class="mt-3">
                            <a href="{{ route('transactions.index') }}" class="quiet-link text-[14px]">Clear the filters</a>
                        </p>
                    @else
                        <p class="text-[14px] leading-[1.6] text-[#64748B]">Nothing recorded yet. The first entry you write appears here.</p>
                    @endif
                </div>
            @else
                {{-- Bulk selection: fixed at the viewport foot on mobile, a band inside the sheet from sm up. --}}
                <div id="bulk-bar" class="hidden fixed inset-x-0 bottom-0 z-30 flex flex-wrap items-center justify-between gap-x-6 gap-y-2 border-t border-[#E2E8F0] bg-white px-5 py-3 sm:static sm:border-t-0 sm:border-b sm:border-[#F1F5F9] sm:bg-[#F8FAFC] sm:py-2.5">
                    <p class="text-[13px] text-[#64748B]" role="status"><span class="tnum font-medium text-[#0F172A]" data-bulk-count>0</span> selected</p>
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
                        <button type="button" data-bulk-all class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Select all {{ $transactions->count() }} on this page</button>
                        <button type="button" data-bulk-clear class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Clear selection</button>
                        <button type="submit" form="bulk-delete-form" class="text-[13px] font-medium text-[#64748B] hover:text-[#DC2626] transition-colors">Delete selected</button>
                    </div>
                </div>
                <form id="bulk-delete-form" method="POST" action="{{ route('transactions.bulk-destroy') }}"
                      onsubmit="return confirm(@js(__('Delete the selected transactions? This cannot be undone.')))">
                    @csrf
                    @method('DELETE')
                </form>

                {{-- Mobile: each record as a stacked row --}}
                <ul class="sm:hidden">
                    @foreach ($transactions as $transaction)
                        <li class="px-5 py-4 border-b border-[#F1F5F9] last:border-b-0">
                            <div class="flex items-start gap-3">
                                <label class="-m-2.5 flex-none cursor-pointer p-2.5">
                                    <input type="checkbox" form="bulk-delete-form" name="ids[]" value="{{ $transaction->id }}" data-bulk-row class="check-input"
                                           aria-label="Select the transaction of MYR {{ number_format((float) $transaction->amount, 2) }} in {{ $transaction->category->name }} on {{ $transaction->transaction_date->format('M j, Y') }}">
                                </label>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-baseline justify-between gap-x-4">
                                        <p class="text-[13px] tnum text-[#64748B]">{{ $transaction->transaction_date->format('M j, Y') }}</p>
                                        <p class="whitespace-nowrap text-[14px] tnum font-medium {{ $transaction->type === 'Income' ? 'text-[#15803D]' : '' }}">
                                            {{ $transaction->type === 'Income' ? '+' : '−' }}{{ number_format($transaction->amount, 2) }}
                                        </p>
                                    </div>
                                    <p class="mt-1.5 flex items-baseline gap-2 text-[14px]">
                                        <span class="inline-block h-2.5 w-2.5 self-center rounded-[3px]" style="background-color: {{ $transaction->category->color }}" aria-hidden="true"></span>
                                        <span class="font-medium">{{ $transaction->category->name }}</span>
                                    </p>
                                    @if ($transaction->description)
                                        <p class="mt-1 truncate text-[13.5px] text-[#64748B]">{{ $transaction->description }}</p>
                                    @endif
                                    <div class="mt-1.5 flex items-center gap-5">
                                        <a href="{{ route('transactions.index', ['edit' => $transaction->id]) }}" class="py-1.5 text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Edit</a>
                                        @if ($transaction->receipt_path)
                                            <a href="{{ $transaction->receipt_url }}" target="_blank" class="py-1.5 text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">View receipt</a>
                                        @endif
                                        <form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}" class="flex"
                                              onsubmit="return confirm(@js(__('Delete this transaction of :amount in :category?', ['amount' => 'MYR '.number_format((float) $transaction->amount, 2), 'category' => $transaction->category->name])))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="py-1.5 text-[13px] font-medium text-[#64748B] hover:text-[#DC2626] transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Tablet and up: the table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full min-w-[800px] border-collapse text-left">
                        <thead>
                            <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
                                <th scope="col" class="px-5 py-2.5">
                                    <input type="checkbox" data-bulk-master class="check-input" aria-label="Select every transaction on this page">
                                </th>
                                <th scope="col" class="small-label px-5 py-2.5 text-[#64748B]">Date</th>
                                <th scope="col" class="small-label px-5 py-2.5 text-[#64748B]">Category</th>
                                <th scope="col" class="small-label px-5 py-2.5 text-[#64748B]">Description</th>
                                <th scope="col" class="small-label px-5 py-2.5 text-right text-[#64748B]">Amount</th>
                                <th scope="col" class="small-label px-5 py-2.5 text-[#64748B]">Receipt</th>
                                <th scope="col" class="small-label px-5 py-2.5 text-right text-[#64748B]">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr class="border-b border-[#F1F5F9] last:border-b-0 transition-colors hover:bg-[#F8FAFC]">
                                    <td class="px-5 py-3.5">
                                        <input type="checkbox" form="bulk-delete-form" name="ids[]" value="{{ $transaction->id }}" data-bulk-row class="check-input"
                                               aria-label="Select the transaction of MYR {{ number_format((float) $transaction->amount, 2) }} in {{ $transaction->category->name }} on {{ $transaction->transaction_date->format('M j, Y') }}">
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-[14px] tnum text-[#64748B]">
                                        {{ $transaction->transaction_date->format('M j, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-[14px] font-medium">
                                        <span class="mr-2 inline-block h-2.5 w-2.5 rounded-[3px] align-middle" style="background-color: {{ $transaction->category->color }}" aria-hidden="true"></span>{{ $transaction->category->name }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[14px] text-[#64748B]">
                                        @if ($transaction->description)
                                            <span class="block max-w-[360px] truncate" title="{{ $transaction->description }}">{{ $transaction->description }}</span>
                                        @else
                                            <span class="text-[#64748B]">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-right text-[14px] tnum font-medium {{ $transaction->type === 'Income' ? 'text-[#15803D]' : '' }}">
                                        {{ $transaction->type === 'Income' ? '+' : '−' }}{{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        @if ($transaction->receipt_path)
                                            <a href="{{ $transaction->receipt_url }}" target="_blank" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">View</a>
                                        @else
                                            <span class="text-[14px] text-[#64748B]">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="{{ route('transactions.index', ['edit' => $transaction->id]) }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Edit</a>
                                            <form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}" class="flex"
                                                  onsubmit="return confirm(@js(__('Delete this transaction of :amount in :category?', ['amount' => 'MYR '.number_format((float) $transaction->amount, 2), 'category' => $transaction->category->name])))">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[13px] font-medium text-[#64748B] hover:text-[#DC2626] transition-colors">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 px-5 py-3 border-t border-[#F1F5F9]">
                    <p class="text-[12.5px] tnum text-[#64748B]">
                        Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }} · Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }}
                    </p>
                    <div class="flex items-center gap-5">
                        @if ($transactions->onFirstPage())
                            <span class="text-[13px] font-medium text-[#94A3B8]">Previous</span>
                        @else
                            <a href="{{ $transactions->previousPageUrl() }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Previous</a>
                        @endif
                        @if ($transactions->hasMorePages())
                            <a href="{{ $transactions->nextPageUrl() }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Next</a>
                        @else
                            <span class="text-[13px] font-medium text-[#94A3B8]">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Add transaction sheet --}}
    <div id="add-modal" role="dialog" aria-modal="true" aria-labelledby="add-modal-title" class="{{ $errors->any() && ! $editing ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-[#0F172A]/40">
        <div class="flex min-h-full items-start justify-center p-4 sm:py-12">
            <div class="sheet w-full max-w-[480px]">
                <div class="sheet-cap">
                    <h2 id="add-modal-title" class="text-[15px] font-semibold">New entry</h2>
                    <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Close</button>
                </div>

                <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data" data-transaction-form>
                    @csrf

                    <div class="sheet-body">
                        <div class="field">
                            <span class="field-label">Type</span>
                            <div class="mt-2 grid grid-cols-2 gap-3">
                                @foreach (['Income', 'Expense'] as $type)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="{{ $type }}" data-type-toggle class="peer sr-only" @checked(old('type', 'Expense') === $type)>
                                        <span class="block rounded-lg border border-[#E2E8F0] py-2.5 text-center text-[14px] font-medium text-[#334155] transition-colors peer-checked:border-[#2563EB] peer-checked:bg-[#EFF6FF] peer-checked:font-semibold peer-checked:text-[#1D4ED8] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#2563EB]">{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('type')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="category_id" class="field-label">Category</label>
                            <select id="category_id" name="category_id" required data-category-select class="field-input">
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="amount" class="field-label">Amount</label>
                            <input type="number" id="amount" name="amount" step="0.01" min="0.01" inputmode="decimal" placeholder="0.00" required value="{{ old('amount') }}" class="field-input tnum">
                            @error('amount')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="transaction_date" class="field-label">Date</label>
                            <input type="date" id="transaction_date" name="transaction_date" required value="{{ old('transaction_date', now()->format('Y-m-d')) }}" class="field-input tnum">
                            @error('transaction_date')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="description" class="field-label">Description · optional</label>
                            <input type="text" id="description" name="description" maxlength="255" placeholder="e.g. Weekly groceries" value="{{ old('description') }}" class="field-input">
                            @error('description')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="receipt" class="field-label">Receipt · optional · max 2 MB</label>
                            <input type="file" id="receipt" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                                   class="field-input text-[13px] text-[#64748B] file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-[#F1F5F9] file:px-3 file:py-1.5 file:font-[inherit] file:text-[13px] file:font-medium file:text-[#334155] hover:file:bg-[#E2E8F0]">
                            @error('receipt')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-block">Save entry</button>

                    <div class="sheet-foot flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                        <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="quiet-link text-[12.5px]">Cancel</button>
                        <span>Amounts in MYR.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($editing)
        {{-- Edit transaction sheet --}}
        <div id="edit-modal" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title" class="fixed inset-0 z-50 overflow-y-auto bg-[#0F172A]/40">
            <div class="flex min-h-full items-start justify-center p-4 sm:py-12">
                <div class="sheet w-full max-w-[480px]">
                    <div class="sheet-cap">
                        <h2 id="edit-modal-title" class="text-[15px] font-semibold">Edit entry</h2>
                        <a href="{{ route('transactions.index') }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Close</a>
                    </div>

                    <form method="POST" action="{{ route('transactions.update', $editing->id) }}" enctype="multipart/form-data" data-transaction-form>
                        @csrf
                        @method('PUT')

                        <div class="sheet-body">
                            <div class="field">
                                <span class="field-label">Type</span>
                                <div class="mt-2 grid grid-cols-2 gap-3">
                                    @foreach (['Income', 'Expense'] as $type)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="type" value="{{ $type }}" data-type-toggle class="peer sr-only" @checked(old('type', $editing->type) === $type)>
                                            <span class="block rounded-lg border border-[#E2E8F0] py-2.5 text-center text-[14px] font-medium text-[#334155] transition-colors peer-checked:border-[#2563EB] peer-checked:bg-[#EFF6FF] peer-checked:font-semibold peer-checked:text-[#1D4ED8] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#2563EB]">{{ $type }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('type')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="edit_category_id" class="field-label">Category</label>
                                <select id="edit_category_id" name="category_id" required data-category-select class="field-input">
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-type="{{ $category->type }}" @selected(old('category_id', $editing->category_id) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="edit_amount" class="field-label">Amount</label>
                                <input type="number" id="edit_amount" name="amount" step="0.01" min="0.01" inputmode="decimal" required value="{{ old('amount', $editing->amount) }}" class="field-input tnum">
                                @error('amount')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="edit_transaction_date" class="field-label">Date</label>
                                <input type="date" id="edit_transaction_date" name="transaction_date" required value="{{ old('transaction_date', $editing->transaction_date->format('Y-m-d')) }}" class="field-input tnum">
                                @error('transaction_date')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="edit_description" class="field-label">Description · optional</label>
                                <input type="text" id="edit_description" name="description" maxlength="255" placeholder="e.g. Weekly groceries" value="{{ old('description', $editing->description) }}" class="field-input">
                                @error('description')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="edit_receipt" class="field-label">Receipt · optional · max 2 MB</label>
                                @if ($editing->receipt_path)
                                    <p class="mb-2 mt-1 text-[13.5px]">
                                        <a href="{{ $editing->receipt_url }}" target="_blank" class="quiet-link">View current receipt</a>
                                    </p>
                                @endif
                                <input type="file" id="edit_receipt" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                                       class="field-input text-[13px] text-[#64748B] file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-[#F1F5F9] file:px-3 file:py-1.5 file:font-[inherit] file:text-[13px] file:font-medium file:text-[#334155] hover:file:bg-[#E2E8F0]">
                                @error('receipt')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-block">Save changes</button>

                        <div class="sheet-foot flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                            <a href="{{ route('transactions.index') }}" class="quiet-link text-[12.5px]">Cancel</a>
                            <span>{{ $editing->receipt_path ? 'An empty file field keeps the current receipt.' : 'Amounts in MYR.' }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Filter categories by the selected transaction type (works for both sheets)
        document.querySelectorAll('[data-transaction-form]').forEach(function (form) {
            const typeRadios = form.querySelectorAll('[data-type-toggle]');
            const categorySelect = form.querySelector('[data-category-select]');
            const initialCategoryId = categorySelect.value;

            function filterCategories() {
                const selectedType = form.querySelector('[data-type-toggle]:checked').value;

                [...categorySelect.options].forEach(option => {
                    if (!option.dataset.type) return; // "Select category" placeholder
                    option.hidden = option.dataset.type !== selectedType;
                });

                const wanted = categorySelect.options[categorySelect.selectedIndex]?.dataset.type;
                if (initialCategoryId && (!categorySelect.value || wanted !== selectedType)) {
                    const preferred = categorySelect.querySelector('option[value="' + initialCategoryId + '"]');
                    if (preferred && !preferred.hidden) preferred.selected = true;
                }
            }

            typeRadios.forEach(radio => radio.addEventListener('change', filterCategories));
            filterCategories();
        });
    </script>

    <script>
        // Bulk selection: the mobile list and the table show the same checkboxes, mirrored;
        // the bar reveals itself with a live count, a select-all, and a clear.
        (function () {
            const bar = document.getElementById('bulk-bar');
            const form = document.getElementById('bulk-delete-form');

            if (!bar || !form) {
                return;
            }

            const boxes = [...document.querySelectorAll('[data-bulk-row]')];
            const master = document.querySelector('[data-bulk-master]');
            const countEl = bar.querySelector('[data-bulk-count]');
            const total = new Set(boxes.map(box => box.value)).size;

            function selectedCount() {
                return new Set(boxes.filter(box => box.checked).map(box => box.value)).size;
            }

            function refresh() {
                const count = selectedCount();

                countEl.textContent = count;
                bar.classList.toggle('hidden', count === 0);

                if (master) {
                    master.checked = count > 0 && count === total;
                    master.indeterminate = count > 0 && count < total;
                }
            }

            boxes.forEach(box => box.addEventListener('change', function () {
                // Mirror the choice onto the same transaction's other checkbox.
                boxes.forEach(twin => {
                    if (twin !== box && twin.value === box.value) {
                        twin.checked = box.checked;
                    }
                });

                refresh();
            }));

            if (master) {
                master.addEventListener('change', function () {
                    boxes.forEach(box => box.checked = master.checked);
                    refresh();
                });
            }

            bar.querySelector('[data-bulk-all]').addEventListener('click', function () {
                boxes.forEach(box => box.checked = true);
                refresh();
            });

            bar.querySelector('[data-bulk-clear]').addEventListener('click', function () {
                boxes.forEach(box => box.checked = false);
                refresh();
            });
        })();
    </script>
</x-app-layout>
