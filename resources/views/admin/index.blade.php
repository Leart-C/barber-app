<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto max-w-xl px-6 py-10">
        <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

        <div class="space-y-3">
            <a href="{{ route('admin.appointments') }}"
                class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                Appointments
            </a>

            <a href="{{ route('admin.today') }}"
                class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                Today Dashboard
            </a>

            <a href="{{ route('admin.customers.index') }}"
                class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                Customer Audit
            </a>

            <a href="{{ route('admin.unavailable') }}"
                class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                Unavailable Times
            </a>

            <a href="{{ route('admin.services.index') }}"
                class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                Price List
            </a>

            <a href="{{ route('admin.revenue') }}"
                class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                Revenue
            </a>

        </div>
    </div>
</body>

</html>
