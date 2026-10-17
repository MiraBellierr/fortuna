<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fortuna · Monthly summary · {{ $monthName }}</title>
    <style>
        /* Rendered by dompdf: DejaVu Sans (its bundled face — the app's woff2 subsets cannot be embedded),
           tables instead of flex/grid, px units only. Numeric columns are right-aligned, standing in for tabular numerals. */
        @page { margin: 0; }

        body {
            margin: 0;
            padding: 44px 48px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.55;
            color: #0F172A;
        }

        .head { border-bottom: 1px solid #E2E8F0; padding-bottom: 20px; }

        .head-table { width: 100%; border-collapse: collapse; }
        .head-table td { padding: 0; vertical-align: bottom; }

        .wordmark { font-size: 21px; font-weight: bold; letter-spacing: -0.5px; }
        .doc-tag { text-align: right; font-size: 9px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; color: #64748B; }

        h1 { margin: 16px 0 0; font-size: 24px; letter-spacing: -0.4px; }

        .deck { margin: 6px 0 0; font-size: 10.5px; color: #64748B; }

        h2 { margin: 0 0 10px; font-size: 12px; }

        .section { margin-top: 28px; page-break-inside: avoid; }

        .figures { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 4px; }
        .figures td { width: 25%; border: 1px solid #E2E8F0; background: #F8FAFC; padding: 12px 13px; vertical-align: top; }

        .fig-label { margin: 0; font-size: 8.5px; font-weight: bold; letter-spacing: 0.8px; text-transform: uppercase; color: #64748B; }
        .fig-value { margin: 8px 0 0; font-size: 15px; font-weight: bold; }
        .fig-value .cur { font-size: 9px; font-weight: normal; color: #64748B; }
        .fig-value.neg { color: #DC2626; }
        .fig-value.none { color: #64748B; }
        .fig-note { margin: 6px 0 0; font-size: 9px; color: #64748B; }

        .data { width: 100%; border-collapse: collapse; page-break-inside: avoid; }
        .data tr { page-break-inside: avoid; }
        .data th {
            padding: 0 0 7px;
            border-bottom: 1px solid #E2E8F0;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #64748B;
            text-align: left;
        }
        .data td { padding: 9px 0; border-bottom: 1px solid #F1F5F9; vertical-align: top; }
        .data .num { text-align: right; }
        .data td.num { white-space: nowrap; }
        .data td.muted { color: #64748B; }
        .data td.neg { color: #DC2626; font-weight: bold; }

        .over-note { display: block; margin-top: 2px; font-size: 9px; color: #DC2626; }

        .meter { width: 72px; height: 5px; margin: 5px 0 0 auto; background: #F1F5F9; }
        .meter span { display: block; height: 5px; background: #2563EB; }
        .meter.over span { background: #DC2626; }

        .empty { margin: 0; padding: 4px 0 10px; font-size: 10.5px; color: #64748B; }

        .foot { margin-top: 30px; padding-top: 12px; border-top: 1px solid #E2E8F0; font-size: 9.5px; color: #64748B; }
    </style>
</head>
<body>
    <div class="head">
        <table class="head-table">
            <tr>
                <td class="wordmark">Fortuna</td>
                <td class="doc-tag">Monthly summary</td>
            </tr>
        </table>
        <h1>{{ $monthName }}</h1>
        <p class="deck">Prepared for {{ $userName }} &middot; {{ $entryCount }} {{ Str::plural('entry', $entryCount) }} recorded.</p>
    </div>

    <div class="section">
        <table class="figures">
            <tr>
                <td>
                    <p class="fig-label">Income this month</p>
                    <p class="fig-value"><span class="cur">MYR</span> {{ number_format($income, 2) }}</p>
                    <p class="fig-note">Everything earned in {{ $monthOnly }}.</p>
                </td>
                <td>
                    <p class="fig-label">Spent this month</p>
                    <p class="fig-value"><span class="cur">MYR</span> {{ number_format($spend, 2) }}</p>
                    <p class="fig-note">Everything recorded in {{ $monthOnly }}.</p>
                </td>
                <td>
                    <p class="fig-label">Net this month</p>
                    <p class="fig-value {{ $net < 0 ? 'neg' : '' }}"><span class="cur">MYR</span> {{ number_format($net, 2) }}</p>
                    <p class="fig-note">Income minus expenses this month.</p>
                </td>
                <td>
                    <p class="fig-label">Budget remaining</p>
                    @if ($budgetRemaining === null)
                        <p class="fig-value none">&mdash;</p>
                        <p class="fig-note">No budgets set for {{ $monthOnly }}.</p>
                    @else
                        <p class="fig-value {{ $budgetRemaining < 0 ? 'neg' : '' }}"><span class="cur">MYR</span> {{ number_format($budgetRemaining, 2) }}</p>
                        <p class="fig-note">This month's budget total minus everything spent.</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>This month's budgets</h2>

        @if ($budgets->isEmpty())
            <p class="empty">No budgets set for {{ $monthName }}.</p>
        @else
            <table class="data">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="num">Spent</th>
                        <th class="num">Limit</th>
                        <th class="num">Used</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($budgets as $budget)
                        @php
                            $spent = (float) $budget->spent;
                            $limit = (float) $budget->amount;
                            $over = $spent > $limit;
                            $rawPercent = $limit > 0 ? (int) round($spent / $limit * 100) : 100;
                        @endphp
                        <tr>
                            <td>
                                {{ $budget->category->name }}
                                @if ($over)
                                    <span class="over-note">Past the line by MYR {{ number_format($spent - $limit, 2) }}.</span>
                                @endif
                            </td>
                            <td class="num {{ $over ? 'neg' : '' }}">{{ number_format($spent, 2) }}</td>
                            <td class="num muted">{{ number_format($limit, 2) }}</td>
                            <td class="num">
                                {{ $rawPercent }}%
                                <div class="meter {{ $over ? 'over' : '' }}">
                                    @if ($rawPercent > 0)
                                        <span style="width: {{ min(100, $rawPercent) }}%"></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="section">
        <h2>Where it went</h2>

        @if ($topCategories->isEmpty())
            <p class="empty">Nothing recorded as spent in {{ $monthOnly }}.</p>
        @else
            <table class="data">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="num">Spent</th>
                        <th class="num">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topCategories as $category)
                        <tr>
                            <td>{{ $category['name'] }}</td>
                            <td class="num">{{ number_format($category['amount'], 2) }}</td>
                            <td class="num muted">{{ $category['share'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <p class="foot">Amounts in MYR &middot; Prepared for {{ $userName }} on {{ $generatedAt->format('M j, Y H:i') }}.</p>
</body>
</html>
