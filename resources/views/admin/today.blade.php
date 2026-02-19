<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Today</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto max-w-4xl px-6 py-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Admin</p>
                <h1 class="mt-2 text-2xl font-semibold">Today</h1>
                <p class="mt-1 text-sm text-[var(--muted)]">Overview of today’s appointments.</p>
            </div>
            <a href="{{ route('admin.appointments') }}"
                class="w-full sm:w-auto text-center inline-flex justify-center items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
                All appointments
            </a>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-5 mb-6">
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 text-center">
                <p class="text-xs text-[var(--muted)]">Total</p>
                <p class="text-xl font-semibold">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 text-center">
                <p class="text-xs text-[var(--muted)]">Booked</p>
                <p class="text-xl font-semibold">{{ $stats['booked'] }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 text-center">
                <p class="text-xs text-[var(--muted)]">Pending</p>
                <p class="text-xl font-semibold">{{ $stats['pending'] }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 text-center">
                <p class="text-xs text-[var(--muted)]">Done</p>
                <p class="text-xl font-semibold">{{ $stats['done'] }}</p>
            </div>
            <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 text-center">
                <p class="text-xs text-[var(--muted)]">Canceled</p>
                <p class="text-xl font-semibold">{{ $stats['canceled'] }}</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($todayAppointments as $appointment)
                <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4">
                    <p class="text-sm text-[var(--muted)]">{{ $appointment->start_at->format('H:i') }}</p>
                    <p class="font-medium">{{ $appointment->customer_name }} – {{ $appointment->service->name ?? '-' }}
                    </p>
                    <p class="text-sm text-[var(--muted)]">{{ $appointment->customer_phone }}</p>
                    <p class="text-xs text-[var(--muted)]">Status: {{ ucfirst($appointment->status) }}</p>
                </div>
            @empty
                <div
                    class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-6 text-center text-[var(--muted)]">
                    No appointments today.
                </div>
            @endforelse
        </div>
    </div>
</body>

</html>
