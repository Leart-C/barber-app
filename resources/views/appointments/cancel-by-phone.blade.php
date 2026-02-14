<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Appointment</title>
    @vite('resources/css/app.css')
</head>
<div class="mt-4 flex justify-center">
    <a href="{{ route('booking') }}"
        class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
        Booking
    </a>
</div>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto w-full max-w-lg px-5 py-10">
        <div class="mb-6 text-center">
            <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Manage</p>
            <h1 class="mt-3 text-2xl font-semibold">Cancel or Reschedule</h1>
            <p class="mt-2 text-sm text-[var(--muted)]">Enter your phone to manage your appointment.</p>
        </div>

        @if (session('message'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('message') }}
            </div>
        @endif

        <div
            class="rounded-3xl border border-[var(--line)] bg-[var(--card)] p-5 shadow-[0_12px_40px_rgba(15,23,42,0.08)]">
            <form method="POST" action="{{ route('cancel.by.phone.send') }}" class="grid gap-3 mb-4">
                @csrf
                <label class="text-sm font-medium">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $phone ?? '') }}"
                    class="rounded-xl border border-[var(--line)] px-3 py-2">
                @error('phone')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror

                <button class="rounded-xl bg-[var(--accent)] px-3 py-2 text-white hover:opacity-90">
                    Send Code
                </button>
            </form>

            <form method="POST" action="{{ route('cancel.by.phone.verify') }}" class="grid gap-3">
                @csrf
                <input type="hidden" name="phone" value="{{ old('phone', $phone ?? '') }}">
                <label class="text-sm font-medium">Verification code</label>
                <input type="text" name="code" class="rounded-xl border border-[var(--line)] px-3 py-2"
                    placeholder="6-digit code">
                @error('code')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror

                <button class="rounded-xl border border-[var(--line)] px-3 py-2">
                    Verify
                </button>
            </form>
        </div>

        @if (!empty($verified))
            <div class="mt-6 space-y-4">
                @forelse ($appointments as $appointment)
                    <div
                        class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 shadow-[0_8px_30px_rgba(15,23,42,0.06)]">
                        <p class="text-sm text-[var(--muted)]">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                        <p class="mt-1 font-medium">{{ $appointment->service->name ?? '-' }}</p>

                        <form method="POST" action="{{ route('cancel.by.phone.cancel', $appointment) }}"
                            class="mt-4">
                            @csrf
                            <button
                                class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 hover:bg-rose-100">
                                Cancel Appointment
                            </button>
                        </form>

                        <a href="{{ route('cancel.by.phone.reschedule.form', $appointment) }}?phone={{ $phone ?? '' }}"
                            class="mt-2 block w-full rounded-xl border border-[var(--line)] px-3 py-2 text-center text-[var(--ink)] hover:bg-[var(--accent-soft)]">
                            Reschedule
                        </a>
                    </div>
                @empty
                    <div
                        class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-5 text-center text-[var(--muted)]">
                        No upcoming appointments found.
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    @if (session('play_sound'))
        <audio id="notify-sound" src="/sounds/notify.mp3"></audio>
        <script>
            window.addEventListener('load', () => {
                const audio = document.getElementById('notify-sound');
                if (audio) audio.play().catch(() => {});
            });
        </script>
    @endif
</body>

</html>
