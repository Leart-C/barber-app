<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Audit</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto max-w-4xl px-6 py-10">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Customer Audit</h1>

            <a href="{{ route('admin.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
                Admin Home
            </a>
        </div>

        <div class="space-y-3">
            @forelse ($customers as $customer)
                <a href="{{ route('admin.customers.show', $customer->customer_phone) }}"
                    class="block rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 hover:bg-[var(--accent-soft)]">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium">{{ $customer->customer_name }}</p>
                            <p class="text-sm text-[var(--muted)]">{{ $customer->customer_phone }}</p>
                        </div>
                        <div class="text-sm text-[var(--muted)] text-right">
                            <p>Total: {{ $customer->total_visits }}</p>
                            <p>Last: {{ \Carbon\Carbon::parse($customer->last_visit)->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div
                    class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-5 text-center text-[var(--muted)]">
                    No customers yet.
                </div>
            @endforelse
        </div>
    </div>
</body>

</html>
