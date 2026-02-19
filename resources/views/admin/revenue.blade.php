<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Revenue</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    @include('partials.toast')
    <div class="mx-auto max-w-4xl px-4 py-6 space-y-6 sm:px-6 sm:py-10">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Revenue</h1>
            <a href="{{ route('admin.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm shadow-sm hover:bg-[var(--accent-soft)]">
                Admin Home
            </a>
        </div>

        <div class="grid gap-3 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 sm:p-4">
                <p class="text-sm text-[var(--muted)]">Today</p>
                <p class="text-lg sm:text-xl font-semibold">€{{ number_format($today / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 sm:p-4">
                <p class="text-sm text-[var(--muted)]">This Week</p>
                <p class="text-lg sm:text-xl font-semibold">€{{ number_format($week / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 sm:p-4">
                <p class="text-sm text-[var(--muted)]">This Month</p>
                <p class="text-lg sm:text-xl font-semibold">€{{ number_format($month / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 sm:p-4">
                <p class="text-sm text-[var(--muted)]">This Year</p>
                <p class="text-lg sm:text-xl font-semibold">€{{ number_format($year / 100, 2) }}</p>
            </div>
            <div
                class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 sm:p-4 border-emerald-200 bg-emerald-50 text-emerald-900">
                <p class="text-sm text-[var(--muted)]">Net - This Month</p>
                <p class="text-lg sm:text-xl font-semibold">€{{ number_format($netMonth / 100, 2) }}</p>
            </div>
            <div
                class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 sm:p-4 border-emerald-200 bg-emerald-50 text-emerald-900">
                <p class="text-sm text-[var(--muted)]">Avg Ticket</p>
                <p class="text-lg sm:text-xl font-semibold">€{{ number_format($avgTicket / 100, 2) }}</p>
            </div>
        </div>

        <h2>Reports</h2>

        @forelse ($reports as $report)
            <div
                class="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="grid gap-1">
                    <p class="font-medium">{{ $report->month }}</p>
                    <p class="text-sm text-[var(--muted)]">Gross: €{{ number_format($report->gross_cents / 100, 2) }}
                    </p>
                    <p class="text-sm text-[var(--muted)]">Net: €{{ number_format($report->net_cents / 100, 2) }}</p>
                </div>
                <a href="{{ route('admin.revenue.pdf', $report) }}"
                    class="w-full sm:w-auto rounded-lg border border-[var(--line)] px-3 py-2 text-sm hover:bg-[var(--accent-soft)]">
                    Download PDF
                </a>
            </div>
        @empty
            <p>No Reports yet </p>
        @endforelse

        <form method="POST" action="{{ route('admin.revenue.close') }}"
            class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 grid gap-3 sm:grid-cols-3">
            @csrf
            <div class="sm:col-span-2">
                <h1 class="text-base font-semibold">Close Month</h1>
                <p class="text-sm text-[var(--muted)]">Freeze this month's totals into a report</p>
            </div>
            <div class="flex items-end sm:justify-end">
                <button class="w-full sm:w-auto rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Close Month</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.revenue.rent') }}"
            class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 grid gap-3 sm:grid-cols-3">
            @csrf
            <div class="sm:col-span-2">
                <label class="text-sm text-[var(--muted)]">Monthly Rent (€)</label>
                <input type="number" step="0.01" name="monthly_rent_eur"
                    value="{{ old('monthly_rent_eur', $rentEur) }}"
                    class="mt-1 w-full rounded-xl border border-[var(--line)] px-3 py-2">
            </div>
            <div class="flex items-end sm:justify-end">
                <button class="w-full sm:w-auto rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Save</button>
            </div>
        </form>
    </div>
</body>

</html>
