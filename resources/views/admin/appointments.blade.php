<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Appointments</title>

    @vite('resources/css/app.css')
    @livewireStyles
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

        @livewire('admin-appointments-list', ['filters' => $filters ?? []])
    </div>

    <audio id="notify-sound" src="/sounds/notify.mp3"></audio>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('new-appointment', () => {
                const audio = document.getElementById('notify-sound');
                if (audio) audio.play().catch(() => {});
            });
        });
    </script>
</body>

</html>
