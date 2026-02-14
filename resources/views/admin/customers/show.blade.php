<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer History</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto max-w-4xl px-6 py-10">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">{{ $customerName }}</h1>
                <p class="text-[var(--muted)]">{{ $phone }}</p>
            </div>

            <a href="{{ route('admin.customers.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
                Back
            </a>
        </div>

        <div class="space-y-3">
            @forelse ($appointments as $appointment)
                <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4">
                    <p class="text-sm text-[var(--muted)]">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                    <p class="font-medium">{{ $appointment->service->name ?? '-' }}</p>
                    <p class="text-sm text-[var(--muted)]">Status: {{ ucfirst($appointment->status) }}</p>
                    <p class="text-sm text-[var(--muted)]">Notes: {{ $appointment->notes ?? '—' }}</p>
                </div>
            @empty
                <div
                    class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-5 text-center text-[var(--muted)]">
                    No history yet.
                </div>
            @endforelse
        </div>
    </div>
</body>

</html>
