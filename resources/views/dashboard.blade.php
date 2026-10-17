<x-app-layout title="Dashboard | Fortuna">
    @php
        $overCount = $budgets->filter(fn ($budget) => (float) $budget->spent > (float) $budget->amount)->count();
    @endphp

    {{-- Page head --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <h1 class="sheet-title">Your ledger</h1>
            <p class="mt-1.5 text-[14px] leading-[1.6] text-[#64748B]">
                Welcome back, {{ Auth::user()->name }}. {{ $monthName }}, as recorded so far.
            </p>
        </div>
        <a href="{{ route('reports.monthly-summary') }}" class="text-[13px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">Monthly summary (PDF)</a>
    </div>

    {{-- Summary figures --}}
    <section class="mt-8" aria-label="Summary for {{ $monthName }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Total balance</p>
                <p class="figure-value tnum {{ $totalBalance < 0 ? 'text-[#DC2626]' : '' }}"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($totalBalance, 2) }}</p>
                <p class="figure-note">Income minus expenses, all time.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Income this month</p>
                <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($monthlyIncome, 2) }}</p>
                <p class="figure-note">Everything earned in {{ $monthOnly }}.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Spent this month</p>
                <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($monthlySpend, 2) }}</p>
                <p class="figure-note">Everything recorded in {{ $monthOnly }}.</p>
            </div>
            <div class="figure-card">
                <p class="small-label text-[#64748B]">Budget remaining</p>
                @if ($budgetRemaining === null)
                    <p class="figure-value tnum text-[#64748B]">—</p>
                    <p class="figure-note">No budgets set for {{ $monthOnly }}. <a href="{{ route('budgets.index') }}" class="quiet-link">Set budgets</a></p>
                @elseif ($budgetRemaining < 0)
                    <p class="figure-value tnum text-[#DC2626]"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>-{{ number_format(abs($budgetRemaining), 2) }}</p>
                    <p class="figure-note">Past the month's lines by <span class="tnum font-medium text-[#DC2626]">{{ number_format(abs($budgetRemaining), 2) }}</span>.</p>
                @else
                    <p class="figure-value tnum"><span class="mr-1.5 text-[13px] font-medium text-[#64748B]">MYR</span>{{ number_format($budgetRemaining, 2) }}</p>
                    <p class="figure-note">This month's budget total minus everything spent.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- The budget check --}}
    <section class="mt-8" aria-label="This month's budgets">
        <div class="sheet">
            <div class="sheet-cap">
                <h2 class="text-[15px] font-semibold">This month's budgets</h2>
                <span class="text-[13px] tnum text-[#64748B]">{{ $monthName }}</span>
            </div>

            @if ($budgets->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-[14px] leading-[1.6] text-[#64748B]">
                        No budgets for {{ $monthName }} yet. Give a category a monthly line and the check appears here.
                    </p>
                    <p class="mt-3">
                        <a href="{{ route('budgets.index') }}" class="quiet-link text-[14px]">Set budgets</a>
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
                                <p class="tnum whitespace-nowrap {{ $over ? 'font-semibold text-[#DC2626]' : 'text-[#64748B]' }}">
                                    {{ number_format($spent, 2) }} <span class="text-[#94A3B8]" aria-hidden="true">/</span> {{ number_format($limit, 2) }}
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
                        Amounts in MYR.
                        @if ($overCount > 0)
                            {{ $overCount }} {{ Str::plural('line', $overCount) }} past {{ $overCount === 1 ? 'its' : 'their' }} budget.
                        @else
                            All lines are within budget.
                        @endif
                    </p>
                    <a href="{{ route('budgets.index') }}" class="quiet-link text-[12.5px] whitespace-nowrap">Manage budgets</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Charts --}}
    <section class="mt-8" aria-label="Charts">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="sheet">
                <div class="sheet-cap">
                    <h2 class="text-[15px] font-semibold">Spending trend</h2>
                    <select id="trend-year" class="tnum cursor-pointer rounded-md border border-[#E2E8F0] bg-white px-2 py-1 text-[13px] text-[#334155] transition-colors hover:border-[#CBD5E1] focus:outline-none focus:border-[#2563EB] focus:ring-[3px] focus:ring-[#2563EB]/15" aria-label="Year for the spending trend">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" @selected($year === (int) now()->format('Y'))>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="p-5">
                    <div id="trend-chart" class="relative min-h-[300px]">
                        <p class="absolute inset-0 z-10 flex items-center justify-center text-[13px] text-[#64748B]" data-loading>Loading the chart…</p>
                    </div>
                </div>
                <p class="sheet-foot">Income, spending, and the net line through each month. Amounts in MYR.</p>
            </div>

            <div class="sheet">
                <div class="sheet-cap">
                    <h2 class="text-[15px] font-semibold">Where it went</h2>
                    <span class="small-label text-[#64748B]">All time</span>
                </div>
                <div class="p-5">
                    <div id="dist-chart" class="min-h-[300px]">
                        <p class="py-24 text-center text-[13px] text-[#64748B]" data-loading>Loading the chart…</p>
                    </div>
                    <p id="dist-empty" class="hidden py-20 text-center text-[14px] leading-[1.6] text-[#64748B]">
                        No expenses recorded yet. The first expense you file appears here.
                    </p>
                </div>
                <p class="sheet-foot">Expenses by category, colored as your categories are.</p>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.ApexCharts) {
                    return;
                }

                var fontFamily = "'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif";
                var axisLabelStyle = { colors: '#64748B', fontSize: '12px', fontFamily: fontFamily };
                var money = function (value) {
                    return Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                };
                var whole = function (value) {
                    return Number(value).toLocaleString();
                };
                var legend = {
                    position: 'top',
                    horizontalAlign: 'left',
                    fontFamily: fontFamily,
                    fontSize: '12px',
                    fontWeight: 500,
                    markers: { size: 6, shape: 'circle', strokeWidth: 0 },
                    labels: { colors: '#334155' },
                    itemMargin: { horizontal: 12, vertical: 2 }
                };

                function clearLoading(element) {
                    var note = element.querySelector('[data-loading]');
                    if (note) {
                        note.remove();
                    }
                }

                function chartFailed(element) {
                    element.innerHTML = '<p class="py-24 text-center text-[13px] text-[#64748B]">The chart could not be loaded.</p>';
                }

                // Spending trend
                var trendElement = document.getElementById('trend-chart');
                var trendChart = new window.ApexCharts(trendElement, {
                    chart: { type: 'line', height: 300, fontFamily: fontFamily, background: 'transparent', toolbar: { show: false }, zoom: { enabled: false }, animations: { enabled: false } },
                    series: [],
                    colors: ['#94A3B8', '#2563EB', '#64748B'],
                    stroke: { curve: 'straight', width: 2, dashArray: [0, 0, 5] },
                    grid: { borderColor: '#E2E8F0', strokeDashArray: 4, padding: { top: 8, right: 8, bottom: 0, left: 8 } },
                    xaxis: { categories: [], labels: { style: axisLabelStyle }, axisBorder: { color: '#E2E8F0' }, axisTicks: { show: false } },
                    yaxis: { labels: { style: axisLabelStyle, formatter: whole } },
                    legend: legend,
                    dataLabels: { enabled: false },
                    markers: { size: 0, strokeWidth: 0, hover: { size: 4 } },
                    tooltip: { theme: 'light', style: { fontFamily: fontFamily, fontSize: '12px' }, y: { formatter: money } }
                });
                trendChart.render();
                // The loading note stays until the series land; an empty render alone is not "loaded".

                function loadTrend(year) {
                    fetch('/api/reports/monthly?year=' + encodeURIComponent(year), { headers: { 'Accept': 'application/json' } })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Trend request failed');
                            }
                            return response.json();
                        })
                        .then(function (data) {
                            trendChart.updateOptions({ xaxis: { categories: data.chart_data.labels } }, false, false);
                            trendChart.updateSeries(data.chart_data.series);
                            clearLoading(trendElement);
                        })
                        .catch(function () {
                            trendChart.destroy();
                            chartFailed(trendElement);
                        });
                }

                loadTrend(document.getElementById('trend-year').value);

                document.getElementById('trend-year').addEventListener('change', function () {
                    loadTrend(this.value);
                });

                // Expense distribution
                var distElement = document.getElementById('dist-chart');

                fetch('/api/reports/category-distribution', { headers: { 'Accept': 'application/json' } })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Distribution request failed');
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        if (!data.chart_data.series || data.chart_data.series.length === 0) {
                            distElement.classList.add('hidden');
                            document.getElementById('dist-empty').classList.remove('hidden');
                            return;
                        }

                        var distChart = new window.ApexCharts(distElement, {
                            chart: { type: 'donut', height: 300, fontFamily: fontFamily, background: 'transparent', animations: { enabled: false } },
                            series: data.chart_data.series,
                            labels: data.chart_data.labels,
                            colors: data.chart_data.colors,
                            stroke: { width: 2, colors: ['#FFFFFF'] },
                            legend: Object.assign({}, legend, { position: 'bottom', horizontalAlign: 'center', offsetY: 4, itemMargin: { horizontal: 10, vertical: 3 } }),
                            dataLabels: { enabled: false },
                            plotOptions: {
                                pie: {
                                    expandOnClick: false,
                                    donut: {
                                        size: '66%',
                                        labels: {
                                            show: true,
                                            name: { fontFamily: fontFamily, fontSize: '12px', color: '#64748B' },
                                            value: { fontFamily: fontFamily, fontSize: '22px', fontWeight: 600, color: '#0F172A', formatter: money },
                                            total: {
                                                show: true,
                                                label: 'Spent',
                                                fontFamily: fontFamily,
                                                fontSize: '12px',
                                                color: '#64748B',
                                                formatter: function (w) {
                                                    var totals = (w.globals.seriesTotals || []).reduce(function (a, b) { return a + b; }, 0);
                                                    return money(totals);
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            tooltip: { theme: 'light', style: { fontFamily: fontFamily, fontSize: '12px' }, y: { formatter: money } }
                        });
                        distChart.render();
                        clearLoading(distElement);
                    })
                    .catch(function () {
                        document.getElementById('dist-empty').classList.add('hidden');
                        chartFailed(distElement);
                    });
            });
        </script>
    @endpush
</x-app-layout>
