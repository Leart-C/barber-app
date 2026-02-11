<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">

    <title>Admin – Appointments</title>

    @vite('resources/css/app.css')
    @livewireStyles
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Barber">

</head>
<div class="mt-4 flex justify-center">
    <a href="{{ route('admin.today') }}"
        class="inline-flex items-center gap-2 rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
        Today Dashboard
    </a>
</div>


<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto w-full max-w-xl px-5 py-10">
        <div class="mb-6 text-center">
            <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Admin</p>
            <h1 class="mt-3 text-2xl font-semibold">Appointments</h1>
            <p class="mt-2 text-sm text-[var(--muted)]">Live updates, clean and fast.</p>
        </div>

        @if (session('message'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('message') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.appointments') }}"
            class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div>
                <label class="text-sm text-[var(--muted)]">Status</label>
                <select name="status" class="mt-1 w-full rounded-xl border border-[var(--line)] px-3 py-2">
                    <option value="">All</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                    <option value="booked" @selected(($filters['status'] ?? '') === 'booked')>Booked</option>
                    <option value="done" @selected(($filters['status'] ?? '') === 'done')>Done</option>
                    <option value="canceled" @selected(($filters['status'] ?? '') === 'canceled')>Canceled</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-[var(--muted)]">Date</label>
                <input type="date" name="date" value="{{ $filters['date'] ?? '' }}"
                    class="mt-1 w-full rounded-xl border border-[var(--line)] px-3 py-2">
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="w-full rounded-xl bg-[var(--accent)] px-3 py-2 text-white hover:opacity-90">Filter</button>
                <a href="{{ route('admin.appointments') }}"
                    class="w-full rounded-xl border border-[var(--line)] px-3 py-2 text-center">
                    Reset
                </a>
            </div>
        </form>

        @livewire('admin-appointments-list', ['filters' => $filters ?? []])
    </div>

    <audio id="notify-sound" src="/sounds/notify.mp3"></audio>

    <div id="toast"
        class="fixed left-1/2 top-6 z-50 hidden -translate-x-1/2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow">
        New appointment booked
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('new-appointment', () => {
                const audio = document.getElementById('notify-sound');
                if (audio) audio.play().catch(() => {});

                const toast = document.getElementById('toast');
                if (!toast) return;
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 3000);
            });
        });
    </script>


</body>

</html>
