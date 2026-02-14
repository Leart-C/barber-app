<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Revenue</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto max-w-4xl px-6 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Revenue</h1>
            <a href="{{ route('admin.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm shadow-sm hover:bg-[var(--accent-soft)]">
                Admin Home
            </a>
        </div>

        @if (session('message'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 shake">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4">
                <p class="text-sm text-[var(--muted)]">Today</p>
                <p class="text-xl font-semibold">€{{ number_format($today / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4">
                <p class="text-sm text-[var(--muted)]">This Week</p>
                <p class="text-xl font-semibold">€{{ number_format($week / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4">
                <p class="text-sm text-[var(--muted)]">This Month</p>
                <p class="text-xl font-semibold">€{{ number_format($month / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4">
                <p class="text-sm text-[var(--muted)]">This Year</p>
                <p class="text-xl font-semibold">€{{ number_format($year / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 border-emerald-200 bg-emerald-50 text-emerald-900">
                <p class="text-sm text-[var(--muted)] ">Net - This Month</p>
                <p class="text-xl font-semibold text-emerald-900 font-semibold">€{{ number_format($netMonth / 100, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 border-emerald-200 bg-emerald-50 text-emerald-900">
                <p class="text-sm text-[var(--muted)] ">Avg Ticket</p>
                <p class="text-xl font-semibold text-emerald-900 font-semibold">€{{ number_format($avgTicket / 100, 2) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.revenue.close') }}"
            class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 grid gap-3 sm:grid-cols-3">
            @csrf
            <div class="flex items-end">
                <button class="w-full rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Close Month</button>
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
            <div class="flex items-end">
                <button class="w-full rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Save</button>
            </div>
        </form>
    </div>
</body>

</html>
