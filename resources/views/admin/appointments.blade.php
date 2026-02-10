<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Appointments</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto w-full max-w-xl px-4 py-8">
        <div class="mb-6 text-center">
            <p class="text-xs uppercase tracking-widest text-slate-500">Admin</p>
            <h1 class="mt-2 text-2xl font-semibold">Appointments</h1>
            <p class="mt-1 text-sm text-slate-600">Manage bookings quickly on mobile.</p>
        </div>

        @if (session('message'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('message') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.appointments') }}"
            class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div>
                <label class="text-sm text-slate-600">Status</label>
                <select name="status" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">All</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                    <option value="booked" @selected(($filters['status'] ?? '') === 'booked')>Booked</option>
                    <option value="done" @selected(($filters['status'] ?? '') === 'done')>Done</option>
                    <option value="canceled" @selected(($filters['status'] ?? '') === 'canceled')>Canceled</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-slate-600">Date</label>
                <input type="date" name="date" value="{{ $filters['date'] ?? '' }}"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div class="flex items-end gap-2">
                <button class="w-full rounded-lg bg-slate-900 px-3 py-2 text-white hover:bg-slate-800">Filter</button>
                <a href="{{ route('admin.appointments') }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-center">
                    Reset
                </a>
            </div>
        </form>


        <div class="space-y-4">
            @forelse ($appointments as $appointment)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Date & Time</p>
                            <p class="text-base font-medium">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <span
                            class="rounded-full px-2 py-1 text-xs font-medium
                            @if ($appointment->status === 'booked') bg-blue-100 text-blue-700
                            @elseif ($appointment->status === 'pending') bg-amber-100 text-amber-700
                            @elseif ($appointment->status === 'done') bg-emerald-100 text-emerald-700
                            @elseif ($appointment->status === 'canceled') bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="mt-3 grid gap-1 text-sm">
                        <p><span class="text-slate-500">Customer:</span> {{ $appointment->customer_name }}</p>
                        <p><span class="text-slate-500">Phone:</span> {{ $appointment->customer_phone }}</p>
                        <p><span class="text-slate-500">Service:</span> {{ $appointment->service->name ?? '-' }}</p>
                    </div>

                    <div class="mt-4 flex gap-3">
                        @if (in_array($appointment->status, ['pending', 'booked']))
                            <form method="POST" action="{{ route('admin.appointments.done', $appointment) }}"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="w-full rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700 hover:bg-emerald-100">
                                    Done
                                </button>

                            </form>
                        @endif

                        @if ($appointment->status !== 'canceled')
                            <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="w-full rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-700 hover:bg-red-100">
                                    Cancel
                                </button>

                            </form>
                        @endif

                        @if (!in_array($appointment->status, ['pending', 'booked']) && $appointment->status === 'canceled')
                            <div class="flex-1 text-center text-xs text-slate-500">No actions</div>
                        @endif
                    </div>

                </div>
            @empty
                <div class="rounded-xl border border-slate-200 bg-white p-6 text-center text-slate-500">
                    No appointments yet.
                </div>
            @endforelse
        </div>
    </div>
</body>

</html>
