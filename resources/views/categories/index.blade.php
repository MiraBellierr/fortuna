<x-app-layout title="Categories | Fortuna">
    @php
        $palette = ['#0F172A', '#64748B', '#FF6384', '#36A2EB', '#4BC0C0', '#9966FF', '#FF9F40', '#FFCE56', '#97C257', '#457B9D', '#A8DADC', '#E2B1F3'];
        $incomeCount = $categories->where('type', 'Income')->count();
        $expenseCount = $categories->where('type', 'Expense')->count();
    @endphp

    {{-- Page head --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <h1 class="sheet-title">Categories</h1>
            <p class="mt-1.5 text-[14px] leading-[1.6] text-[#64748B]">Income and spending, filed under names you choose. Each color carries into every chart.</p>
        </div>
        <button type="button" onclick="document.getElementById('category-modal').classList.remove('hidden')" class="btn-primary w-full sm:w-auto">New category</button>
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

    {{-- Counts --}}
    <section class="mt-8" aria-label="Category counts">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Total categories</p>
                <p class="figure-value tnum">{{ $categories->count() }}</p>
                <p class="figure-note">Every name in the ledger.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Income</p>
                <p class="figure-value tnum">{{ $incomeCount }}</p>
                <p class="figure-note">Where money comes in.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Expense</p>
                <p class="figure-value tnum">{{ $expenseCount }}</p>
                <p class="figure-note">Where money goes out.</p>
            </div>
        </div>
    </section>

    {{-- The list --}}
    <section class="mt-8" aria-label="All categories">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">The list</h2>
                <span class="text-[13px] tnum text-[#64748B]">{{ $categories->count() }} {{ Str::plural('name', $categories->count()) }}</span>
            </div>

            @if ($categories->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-[14px] leading-[1.6] text-[#64748B]">
                        No categories yet. Categories organise income and spending and carry a color into every chart.
                    </p>
                    <p class="mt-3">
                        <button type="button" onclick="document.getElementById('category-modal').classList.remove('hidden')" class="quiet-link text-[14px]">Create the first one</button>
                    </p>
                </div>
            @else
                <div class="px-5 py-2">
                    @foreach ($categories as $category)
                        @php $inUse = $category->transactions_count > 0 || $category->budgets_count > 0; @endphp
                        <div class="py-3.5 {{ $loop->last ? '' : 'border-b border-[#F1F5F9]' }}">
                            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2">
                                <p class="flex items-baseline gap-3 text-[14px]">
                                    <span class="inline-block h-2.5 w-2.5 self-center rounded-[3px]" style="background-color: {{ $category->color ?? '#64748B' }}" aria-hidden="true"></span>
                                    <span class="font-medium">{{ $category->name }}</span>
                                    <span class="small-label text-[#64748B]">{{ $category->type }}</span>
                                </p>
                                <div class="flex items-baseline gap-x-5 whitespace-nowrap">
                                    <p class="text-[12.5px] tnum text-[#64748B]">
                                        {{ $category->transactions_count }} {{ Str::plural('transaction', $category->transactions_count) }}
                                        · {{ $category->budgets_count }} {{ Str::plural('budget', $category->budgets_count) }}
                                    </p>
                                    <a href="{{ route('categories.index', ['edit' => $category->id]) }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('categories.destroy', $category->id) }}"
                                          onsubmit="return confirm(@js(__('Delete the category ":name"?', ['name' => $category->name])))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" @disabled($inUse) @if ($inUse) title="This category is in use and cannot be deleted" @endif
                                                class="text-[13px] font-medium transition-colors {{ $inUse ? 'cursor-not-allowed text-[#94A3B8]' : 'text-[#64748B] hover:text-[#DC2626]' }}">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t border-[#F1F5F9]">
                    <p class="text-[12.5px] leading-relaxed text-[#64748B]">A category still used by transactions or budgets keeps its name and cannot be deleted.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- New category sheet --}}
    <div id="category-modal" role="dialog" aria-modal="true" aria-labelledby="category-modal-title" class="{{ $errors->any() && ! $editing ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-[#0F172A]/40">
        <div class="flex min-h-full items-start justify-center p-4 sm:py-12">
            <div class="sheet w-full max-w-[480px]">
                <div class="sheet-cap">
                    <h2 id="category-modal-title" class="text-[15px] font-semibold">New category</h2>
                    <button type="button" onclick="document.getElementById('category-modal').classList.add('hidden')" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Close</button>
                </div>

                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    <div class="sheet-body">
                        <div class="field">
                            <label for="name" class="field-label">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Groceries" class="field-input">
                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <span class="field-label">Type</span>
                            <div class="mt-2 grid grid-cols-2 gap-3">
                                @foreach (['Income', 'Expense'] as $type)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="{{ $type }}" class="peer sr-only" @checked(old('type', 'Expense') === $type)>
                                        <span class="block rounded-lg border border-[#E2E8F0] py-2.5 text-center text-[14px] font-medium text-[#334155] transition-colors peer-checked:border-[#2563EB] peer-checked:bg-[#EFF6FF] peer-checked:font-semibold peer-checked:text-[#1D4ED8] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#2563EB]">{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('type')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <span class="field-label">Color</span>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                @foreach ($palette as $color)
                                    <label class="cursor-pointer" title="{{ $color }}">
                                        <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" @checked(old('color', '#0F172A') === $color)>
                                        <span class="block h-6 w-6 rounded-md border border-black/10 transition-all peer-checked:outline peer-checked:outline-2 peer-checked:outline-offset-2 peer-checked:outline-[#2563EB] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#2563EB]" style="background-color: {{ $color }}"></span>
                                    </label>
                                @endforeach
                            </div>
                            @error('color')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-block">Save category</button>

                    <div class="sheet-foot flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                        <button type="button" onclick="document.getElementById('category-modal').classList.add('hidden')" class="quiet-link text-[12.5px]">Cancel</button>
                        <span>The color marks this category across the charts.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($editing)
        {{-- Edit category sheet --}}
        <div id="edit-modal" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title" class="fixed inset-0 z-50 overflow-y-auto bg-[#0F172A]/40">
            <div class="flex min-h-full items-start justify-center p-4 sm:py-12">
                <div class="sheet w-full max-w-[480px]">
                    <div class="sheet-cap">
                        <h2 id="edit-modal-title" class="text-[15px] font-semibold">Edit category</h2>
                        <a href="{{ route('categories.index') }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Close</a>
                    </div>

                    <form method="POST" action="{{ route('categories.update', $editing->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="sheet-body">
                            <div class="field">
                                <label for="edit-name" class="field-label">Name</label>
                                <input type="text" id="edit-name" name="name" value="{{ old('name', $editing->name) }}" required class="field-input">
                                @error('name')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <span class="field-label">Type</span>
                                <div class="mt-2 grid grid-cols-2 gap-3">
                                    @foreach (['Income', 'Expense'] as $type)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="type" value="{{ $type }}" class="peer sr-only" @checked(old('type', $editing->type) === $type)>
                                            <span class="block rounded-lg border border-[#E2E8F0] py-2.5 text-center text-[14px] font-medium text-[#334155] transition-colors peer-checked:border-[#2563EB] peer-checked:bg-[#EFF6FF] peer-checked:font-semibold peer-checked:text-[#1D4ED8] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#2563EB]">{{ $type }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('type')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <span class="field-label">Color</span>
                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    @foreach ($palette as $color)
                                        <label class="cursor-pointer" title="{{ $color }}">
                                            <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" @checked(old('color', $editing->color ?? '#0F172A') === $color)>
                                            <span class="block h-6 w-6 rounded-md border border-black/10 transition-all peer-checked:outline peer-checked:outline-2 peer-checked:outline-offset-2 peer-checked:outline-[#2563EB] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#2563EB]" style="background-color: {{ $color }}"></span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('color')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-block">Save changes</button>

                        <div class="sheet-foot flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                            <a href="{{ route('categories.index') }}" class="quiet-link text-[12.5px]">Cancel</a>
                            <span>The color marks this category across the charts.</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
