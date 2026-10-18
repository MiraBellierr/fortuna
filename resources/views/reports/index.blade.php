<x-app-layout title="Reports | Fortuna">
    @php
        $hasEntries = $totals['entries'] > 0;
        $netSigned = fn (float $value): string => number_format($value, 2);

        // The movement sentence for the year-by-year foot: only the figures that exist against an earlier year.
        $movementParts = [];
        if ($movement !== null) {
            $signedPercent = fn (?float $value): ?string => $value === null
                ? null
                : ($value > 0 ? '+' : '').number_format($value, 1).'%';
            $signedPoints = fn (?float $value): ?string => $value === null
                ? null
                : ($value > 0 ? '+' : '').number_format($value, 1).' pt';

            foreach ([
                ['income', $signedPercent($movement['income'])],
                ['spending', $signedPercent($movement['expense'])],
                ['net', $signedPercent($movement['net'])],
                ['rate', $signedPoints($movement['savings_rate'])],
            ] as [$label, $formatted]) {
                if ($formatted !== null) {
                    $movementParts[] = $label.' '.$formatted;
                }
            }
        }
    @endphp

    {{-- Page head --}}
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="sheet-title">Reports</h1>
            <p class="mt-1.5 text-[14px] leading-[1.6] text-[#64748B]">The year {{ $year }}, month by month, in one register.</p>
        </div>
        <form method="GET" action="{{ route('reports.index') }}">
            <label for="year" class="field-label">Year</label>
            <select id="year" name="year" onchange="this.form.submit()" class="field-input tnum" style="width: auto;">
                @foreach ($years as $option)
                    <option value="{{ $option }}" @selected($option === $year)>{{ $option }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- The year's figures --}}
    <section class="mt-8" aria-label="Figures for {{ $year }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Total income</p>
                <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totals['income'], 2) }}</p>
                <p class="figure-note">Everything earned in {{ $year }}.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Total spent</p>
                <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totals['expense'], 2) }}</p>
                <p class="figure-note">Everything recorded as spent.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Net saved</p>
                <p class="figure-value tnum {{ $totals['net'] < 0 ? 'text-[#DC2626]' : '' }}"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totals['net'], 2) }}</p>
                <p class="figure-note">Income minus expenses for the year.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Savings rate</p>
                @if ($totals['savings_rate'] === null)
                    <p class="figure-value tnum text-[#64748B]">—</p>
                    <p class="figure-note">No income to rate in {{ $year }} yet.</p>
                @else
                    <p class="figure-value tnum {{ $totals['savings_rate'] < 0 ? 'text-[#DC2626]' : '' }}">{{ number_format($totals['savings_rate'], 1) }}%</p>
                    <p class="figure-note">The share of income kept in {{ $year }}.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- The year's flow --}}
    <section class="mt-8" aria-label="The year in months">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">The year in months</h2>
                <span class="text-[13px] tnum text-[#64748B]">{{ $year }}</span>
            </div>

            @if (! $hasEntries)
                <div class="px-5 py-16 text-center">
                    <p class="text-[14px] leading-[1.6] text-[#64748B]">Nothing recorded in {{ $year }} yet. The first entry you file draws the year here, month by month.</p>
                    <p class="mt-3">
                        <a href="{{ route('transactions.index') }}" class="quiet-link text-[14px]">Record an entry</a>
                    </p>
                </div>
            @else
                <div class="flex flex-wrap items-center gap-x-[18px] gap-y-2 px-5 pt-4" aria-hidden="true">
                    <span class="inline-flex items-center gap-[7px] text-[12px] font-medium text-[#334155]"><span class="inline-block h-[10px] w-[10px] rounded-full bg-[#94A3B8]"></span>Income</span>
                    <span class="inline-flex items-center gap-[7px] text-[12px] font-medium text-[#334155]"><span class="inline-block h-[10px] w-[10px] rounded-full bg-[#2563EB]"></span>Spend</span>
                    <span class="inline-flex items-center gap-[7px] text-[12px] font-medium text-[#334155]"><span class="inline-block h-0 w-[14px] border-t-2 border-dashed border-[#64748B]"></span>Net</span>
                </div>

                <div class="px-5 pb-2 pt-1">
                    <div id="year-flow-chart" class="relative min-h-[340px]">
                        <x-chart-note class="absolute inset-0 z-10 flex items-center justify-center" />
                    </div>
                </div>
                <p class="sheet-foot">Income and spending by month, with the net line through them. Amounts in MYR.</p>
            @endif
        </div>
    </section>

    {{-- The twelve months --}}
    <section class="mt-8" aria-label="The twelve months of {{ $year }}">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">The twelve months</h2>
                <span class="text-[13px] tnum text-[#64748B]">{{ $year }}</span>
            </div>

            <div class="hidden items-baseline gap-x-4 border-b border-[#E2E8F0] bg-[#F8FAFC] px-5 py-2.5 sm:grid sm:grid-cols-[minmax(0,3fr)_64px_repeat(3,minmax(0,1.6fr))_minmax(0,1fr)]">
                <span class="small-label text-[#64748B]">Month</span>
                <span class="small-label text-[#64748B]">Entries</span>
                <span class="small-label text-right text-[#64748B]">Income</span>
                <span class="small-label text-right text-[#64748B]">Spent</span>
                <span class="small-label text-right text-[#64748B]">Net</span>
                <span class="small-label text-right text-[#64748B]">Rate</span>
            </div>

            @foreach ($months as $month)
                <a href="{{ route('transactions.index', ['start_date' => $month['start'], 'end_date' => $month['end']]) }}"
                   class="block px-5 py-3.5 transition-colors hover:bg-[#F8FAFC] {{ $loop->last ? '' : 'border-b border-[#F1F5F9]' }}">
                    {{-- Mobile: the month stacked --}}
                    <div class="flex items-baseline justify-between gap-x-4 sm:hidden">
                        <p class="min-w-0 truncate text-[14px] font-medium {{ $month['entries'] === 0 ? 'text-[#64748B]' : '' }}">
                            {{ $month['name'] }}
                            <span class="text-[13px] font-normal tnum text-[#64748B]">· {{ $month['entries'] === 0 ? 'no entries' : $month['entries'].' '.Str::plural('entry', $month['entries']) }}</span>
                        </p>
                        <p class="whitespace-nowrap text-[14px] tnum font-medium {{ $month['net'] < 0 ? 'text-[#DC2626]' : '' }}">{{ $month['entries'] === 0 ? '—' : $netSigned((float) $month['net']) }}</p>
                    </div>
                    @if ($month['entries'] > 0)
                        <p class="mt-1 text-[12.5px] tnum text-[#64748B] sm:hidden">income <span class="text-[#15803D]">+{{ number_format($month['income'], 2) }}</span> · spent {{ number_format($month['expense'], 2) }} · rate {{ $month['savings_rate'] === null ? '—' : number_format($month['savings_rate'], 1).'%' }}</p>
                    @endif

                    {{-- sm and up: the ruled ledger row --}}
                    <div class="hidden items-baseline gap-x-4 sm:grid sm:grid-cols-[minmax(0,3fr)_64px_repeat(3,minmax(0,1.6fr))_minmax(0,1fr)]">
                        <span class="text-[14px] font-medium {{ $month['entries'] === 0 ? 'text-[#64748B]' : '' }}">{{ $month['name'] }}</span>
                        <span class="text-[13px] tnum text-[#64748B]">{{ $month['entries'] === 0 ? '—' : $month['entries'] }}</span>
                        @if ($month['entries'] === 0)
                            <span class="text-right text-[14px] text-[#64748B]">—</span>
                            <span class="text-right text-[14px] text-[#64748B]">—</span>
                            <span class="text-right text-[14px] text-[#64748B]">—</span>
                            <span class="text-right text-[13px] text-[#64748B]">—</span>
                        @else
                            <span class="text-right text-[14px] tnum font-medium text-[#15803D]">+{{ number_format($month['income'], 2) }}</span>
                            <span class="text-right text-[14px] tnum">{{ number_format($month['expense'], 2) }}</span>
                            <span class="text-right text-[14px] tnum font-medium {{ $month['net'] < 0 ? 'text-[#DC2626]' : '' }}">{{ $netSigned((float) $month['net']) }}</span>
                            <span class="text-right text-[13px] tnum text-[#64748B]">{{ $month['savings_rate'] === null ? '—' : number_format($month['savings_rate'], 1).'%' }}</span>
                        @endif
                    </div>
                    <span class="sr-only">Open {{ $month['name'] }} {{ $year }} in the transaction list.</span>
                </a>
            @endforeach

            {{-- The year's column close --}}
            <div class="border-t border-[#E2E8F0] bg-[#F8FAFC] px-5 py-3.5">
                <div class="flex items-baseline justify-between gap-x-4 sm:hidden">
                    <p class="min-w-0 truncate text-[14px] font-medium">
                        The year
                        <span class="text-[13px] font-normal tnum text-[#64748B]">· {{ $hasEntries ? $totals['entries'].' '.Str::plural('entry', $totals['entries']) : 'no entries' }}</span>
                    </p>
                    <p class="whitespace-nowrap text-[14px] tnum font-medium {{ $totals['net'] < 0 ? 'text-[#DC2626]' : '' }}">{{ $hasEntries ? $netSigned($totals['net']) : '—' }}</p>
                </div>
                @if ($hasEntries)
                    <p class="mt-1 text-[12.5px] tnum text-[#64748B] sm:hidden">income <span class="text-[#15803D]">+{{ number_format($totals['income'], 2) }}</span> · spent {{ number_format($totals['expense'], 2) }} · rate {{ $totals['savings_rate'] === null ? '—' : number_format($totals['savings_rate'], 1).'%' }}</p>
                @endif
                <div class="hidden items-baseline gap-x-4 sm:grid sm:grid-cols-[minmax(0,3fr)_64px_repeat(3,minmax(0,1.6fr))_minmax(0,1fr)]">
                    <span class="text-[14px] font-medium">The year</span>
                    <span class="text-[13px] tnum text-[#64748B]">{{ $hasEntries ? $totals['entries'] : '—' }}</span>
                    @if (! $hasEntries)
                        <span class="text-right text-[14px] text-[#64748B]">—</span>
                        <span class="text-right text-[14px] text-[#64748B]">—</span>
                        <span class="text-right text-[14px] text-[#64748B]">—</span>
                        <span class="text-right text-[13px] text-[#64748B]">—</span>
                    @else
                        <span class="text-right text-[14px] tnum font-medium text-[#15803D]">+{{ number_format($totals['income'], 2) }}</span>
                        <span class="text-right text-[14px] tnum font-medium">{{ number_format($totals['expense'], 2) }}</span>
                        <span class="text-right text-[14px] tnum font-medium {{ $totals['net'] < 0 ? 'text-[#DC2626]' : '' }}">{{ $netSigned($totals['net']) }}</span>
                        <span class="text-right text-[13px] tnum text-[#64748B]">{{ $totals['savings_rate'] === null ? '—' : number_format($totals['savings_rate'], 1).'%' }}</span>
                    @endif
                </div>
            </div>

            <p class="sheet-foot">Every month opens its entries in the transaction list. Amounts in MYR.</p>
        </div>
    </section>

    {{-- Where the money went and came from --}}
    <section class="mt-8" aria-label="Categories for {{ $year }}">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="sheet">
                <div class="sheet-cap">
                    <h2 class="text-[15px] font-semibold">Where it went</h2>
                    <span class="text-[13px] tnum text-[#64748B]">{{ $year }}</span>
                </div>

                @if ($expenseCategories->isEmpty())
                    <div class="px-5 py-12 text-center">
                        <p class="text-[14px] leading-[1.6] text-[#64748B]">No spending recorded in {{ $year }} yet.</p>
                        <p class="mt-3">
                            <a href="{{ route('transactions.index') }}" class="quiet-link text-[14px]">Record an entry</a>
                        </p>
                    </div>
                @else
                    <div class="px-5 py-2">
                        @foreach ($expenseCategories as $category)
                            <div class="py-4 {{ $loop->last ? '' : 'border-b border-[#F1F5F9]' }}">
                                <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                    <p class="flex min-w-0 items-baseline gap-x-2">
                                        <span class="inline-block h-2.5 w-2.5 flex-none self-center rounded-[3px]" style="background-color: {{ $category['color'] }}" aria-hidden="true"></span>
                                        <span class="truncate font-medium">{{ $category['name'] }}</span>
                                    </p>
                                    <p class="whitespace-nowrap font-medium tnum text-[#64748B]">{{ number_format($category['amount'], 2) }}</p>
                                </div>
                                <div class="meter mt-2.5" role="img" aria-label="{{ $category['name'] }}: {{ number_format($category['share'], 1) }} percent of the year's spending">
                                    <span style="width: {{ $category['share'] }}%"></span>
                                </div>
                                <p class="mt-2 text-[12.5px] text-[#64748B]">{{ $category['entries'] }} {{ Str::plural('entry', $category['entries']) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 border-t border-[#F1F5F9] px-5 py-3">
                        <p class="text-[12.5px] leading-relaxed text-[#64748B]">Expenses by category, largest first. Amounts in MYR.</p>
                        <a href="{{ route('transactions.index', ['type' => 'Expense', 'start_date' => $year.'-01-01', 'end_date' => $year.'-12-31']) }}" class="quiet-link whitespace-nowrap text-[12.5px]">Open in the transaction list</a>
                    </div>
                @endif
            </div>

            <div class="sheet">
                <div class="sheet-cap">
                    <h2 class="text-[15px] font-semibold">Where it came from</h2>
                    <span class="text-[13px] tnum text-[#64748B]">{{ $year }}</span>
                </div>

                @if ($incomeCategories->isEmpty())
                    <div class="px-5 py-12 text-center">
                        <p class="text-[14px] leading-[1.6] text-[#64748B]">No income recorded in {{ $year }} yet.</p>
                        <p class="mt-3">
                            <a href="{{ route('transactions.index') }}" class="quiet-link text-[14px]">Record an entry</a>
                        </p>
                    </div>
                @else
                    <div class="px-5 py-2">
                        @foreach ($incomeCategories as $category)
                            <div class="py-4 {{ $loop->last ? '' : 'border-b border-[#F1F5F9]' }}">
                                <div class="flex items-baseline justify-between gap-x-4 text-[14px]">
                                    <p class="flex min-w-0 items-baseline gap-x-2">
                                        <span class="inline-block h-2.5 w-2.5 flex-none self-center rounded-[3px]" style="background-color: {{ $category['color'] }}" aria-hidden="true"></span>
                                        <span class="truncate font-medium">{{ $category['name'] }}</span>
                                    </p>
                                    <p class="whitespace-nowrap font-medium tnum text-[#15803D]">{{ number_format($category['amount'], 2) }}</p>
                                </div>
                                <div class="meter mt-2.5" role="img" aria-label="{{ $category['name'] }}: {{ number_format($category['share'], 1) }} percent of the year's income">
                                    <span style="width: {{ $category['share'] }}%"></span>
                                </div>
                                <p class="mt-2 text-[12.5px] text-[#64748B]">{{ $category['entries'] }} {{ Str::plural('entry', $category['entries']) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 border-t border-[#F1F5F9] px-5 py-3">
                        <p class="text-[12.5px] leading-relaxed text-[#64748B]">Income by category, largest first. Amounts in MYR.</p>
                        <a href="{{ route('transactions.index', ['type' => 'Income', 'start_date' => $year.'-01-01', 'end_date' => $year.'-12-31']) }}" class="quiet-link whitespace-nowrap text-[12.5px]">Open in the transaction list</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- The years --}}
    <section class="mt-8" aria-label="The years in the register">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">The years</h2>
                @if ($yearlyTotals->isNotEmpty())
                    <span class="text-[13px] tnum text-[#64748B]">{{ $yearlyTotals->count() }} {{ Str::plural('year', $yearlyTotals->count()) }}</span>
                @endif
            </div>

            @if ($yearlyTotals->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-[14px] leading-[1.6] text-[#64748B]">No years in the register yet. It opens with your first entry.</p>
                    <p class="mt-3">
                        <a href="{{ route('transactions.index') }}" class="quiet-link text-[14px]">Record an entry</a>
                    </p>
                </div>
            @else
                <div class="hidden items-baseline gap-x-4 border-b border-[#E2E8F0] bg-[#F8FAFC] px-5 py-2.5 sm:grid sm:grid-cols-[minmax(0,3fr)_64px_repeat(3,minmax(0,1.6fr))_minmax(0,1fr)]">
                    <span class="small-label text-[#64748B]">Year</span>
                    <span class="small-label text-[#64748B]">Entries</span>
                    <span class="small-label text-right text-[#64748B]">Income</span>
                    <span class="small-label text-right text-[#64748B]">Spent</span>
                    <span class="small-label text-right text-[#64748B]">Net</span>
                    <span class="small-label text-right text-[#64748B]">Rate</span>
                </div>

                @foreach ($yearlyTotals as $row)
                    <a href="{{ route('reports.index', ['year' => $row['year']]) }}"
                       @if ($row['year'] === $year) aria-current="true" @endif
                       class="block px-5 py-3.5 transition-colors {{ $row['year'] === $year ? 'bg-[#EFF6FF]' : 'hover:bg-[#F8FAFC]' }} {{ $loop->last ? '' : 'border-b border-[#F1F5F9]' }}">
                        {{-- Mobile: the year stacked --}}
                        <div class="flex items-baseline justify-between gap-x-4 sm:hidden">
                            <p class="min-w-0 truncate text-[14px] font-medium {{ $row['year'] === $year ? 'text-[#1D4ED8]' : '' }}">
                                {{ $row['year'] }}
                                <span class="text-[13px] font-normal tnum text-[#64748B]">· {{ $row['entries'] }} {{ Str::plural('entry', $row['entries']) }}</span>
                            </p>
                            <p class="whitespace-nowrap text-[14px] tnum font-medium {{ $row['net'] < 0 ? 'text-[#DC2626]' : '' }}">{{ $netSigned((float) $row['net']) }}</p>
                        </div>
                        <p class="mt-1 text-[12.5px] tnum text-[#64748B] sm:hidden">income <span class="text-[#15803D]">+{{ number_format($row['income'], 2) }}</span> · spent {{ number_format($row['expense'], 2) }} · rate {{ $row['savings_rate'] === null ? '—' : number_format($row['savings_rate'], 1).'%' }}</p>

                        {{-- sm and up: the ruled ledger row --}}
                        <div class="hidden items-baseline gap-x-4 sm:grid sm:grid-cols-[minmax(0,3fr)_64px_repeat(3,minmax(0,1.6fr))_minmax(0,1fr)]">
                            <span class="text-[14px] font-medium {{ $row['year'] === $year ? 'text-[#1D4ED8]' : '' }}">{{ $row['year'] }}</span>
                            <span class="text-[13px] tnum text-[#64748B]">{{ $row['entries'] }}</span>
                            <span class="text-right text-[14px] tnum font-medium text-[#15803D]">+{{ number_format($row['income'], 2) }}</span>
                            <span class="text-right text-[14px] tnum">{{ number_format($row['expense'], 2) }}</span>
                            <span class="text-right text-[14px] tnum font-medium {{ $row['net'] < 0 ? 'text-[#DC2626]' : '' }}">{{ $netSigned((float) $row['net']) }}</span>
                            <span class="text-right text-[13px] tnum text-[#64748B]">{{ $row['savings_rate'] === null ? '—' : number_format($row['savings_rate'], 1).'%' }}</span>
                        </div>
                        <span class="sr-only">Open the {{ $row['year'] }} report.</span>
                    </a>
                @endforeach

                <p class="sheet-foot">
                    @if ($movement !== null && $movementParts !== [])
                        Against {{ $movement['year'] }}: {{ implode(', ', $movementParts) }}. Amounts in MYR.
                    @elseif ($movement !== null)
                        The earlier year has no figures to compare with yet. Amounts in MYR.
                    @else
                        No earlier year in the register to compare with. Amounts in MYR.
                    @endif
                </p>
            @endif
        </div>
    </section>

    @if ($hasEntries)
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (!window.ApexCharts || !window.chartNotes) {
                        return;
                    }

                    var chartNotes = window.chartNotes;
                    var fontFamily = "'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif";
                    var axisLabelStyle = { colors: '#64748B', fontSize: '12px', fontFamily: fontFamily };
                    var money = function (value) {
                        return Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    };
                    var whole = function (value) {
                        return Number(value).toLocaleString();
                    };

                    var flowElement = document.getElementById('year-flow-chart');

                    if (!flowElement) {
                        return;
                    }

                    chartNotes.bind(flowElement);
                    chartNotes.loading(flowElement, 'Loading the chart…');

                    function chartFailed(element) {
                        element.innerHTML = '<p class="py-24 text-center text-[13px] text-[#64748B]">The chart could not be loaded.</p>';
                    }

                    var flowChart = new window.ApexCharts(flowElement, {
                        chart: { type: 'line', height: 340, fontFamily: fontFamily, background: 'transparent', toolbar: { show: false }, zoom: { enabled: false }, animations: { enabled: false } },
                        series: [],
                        colors: ['#94A3B8', '#2563EB', '#64748B'],
                        plotOptions: { bar: { columnWidth: '55%' } },
                        stroke: { curve: 'straight', width: [0, 0, 2], dashArray: [0, 0, 5] },
                        grid: { borderColor: '#E2E8F0', strokeDashArray: 4, padding: { top: 8, right: 8, bottom: 0, left: 8 } },
                        xaxis: { categories: [], labels: { style: axisLabelStyle }, axisBorder: { color: '#E2E8F0' }, axisTicks: { show: false } },
                        yaxis: { labels: { style: axisLabelStyle, formatter: whole } },
                        legend: { show: false },
                        dataLabels: { enabled: false },
                        markers: { size: 0, strokeWidth: 0, hover: { size: 4 } },
                        tooltip: { theme: 'light', style: { fontFamily: fontFamily, fontSize: '12px' }, y: { formatter: money } }
                    });
                    flowChart.render();
                    // The loading note stays until the series land; an empty render alone is not "loaded".

                    fetch('/api/reports/monthly?year=' + encodeURIComponent('{{ $year }}'), { headers: { 'Accept': 'application/json' } })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Flow request failed');
                            }
                            return response.json();
                        })
                        .then(function (data) {
                            // Income and spending as columns, the net as the dashed line over them.
                            var series = data.chart_data.series.map(function (item, index) {
                                return { name: item.name, type: index === 2 ? 'line' : 'column', data: item.data };
                            });

                            flowChart.updateOptions({ xaxis: { categories: data.chart_data.labels } }, false, false);
                            flowChart.updateSeries(series);
                            chartNotes.clear(flowElement);
                        })
                        .catch(function () {
                            chartNotes.clear(flowElement);
                            flowChart.destroy();
                            chartFailed(flowElement);
                        });
                });
            </script>
        @endpush
    @endif
</x-app-layout>
