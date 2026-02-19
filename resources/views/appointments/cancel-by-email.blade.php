<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cancel by Email</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    @include('partials.toast')
    <div class="mt-4 flex justify-center">
        <a href="{{ route('booking') }}"
            class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
            Booking
        </a>
    </div>
    <div class="mx-auto max-w-md px-6 py-10">
        <h1 class="text-2xl font-semibold mb-2">Cancel or Reschedule</h1>
        <p class="text-[var(--muted)] mb-6">Enter your email to manage your appointment.</p>

        <form id="send-code-form" x-data="{ loading: false }" @submit="loading = true" method="POST"
            action="{{ route('cancel.by.email.send') }}" class="grid gap-3 mb-4">
            @csrf

            <label class="text-sm text-[var(--muted)]">Email</label>
            <input type="email" name="email" value="{{ old('email', $email ?? '') }}"
                class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base">

            @error('email')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <button id="send-code-button" type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-2xl bg-[var(--accent)] px-4 py-3 text-base text-white shadow hover:opacity-90"
                :disabled="loading">
                <span id="send-code-text">Send Code</span>
            </button>
        </form>

        <script>
            const form = document.getElementById('send-code-form');
            const btn = document.getElementById('send-code-button');
            const text = document.getElementById('send-code-text');

            if (form && btn && text) {
                form.addEventListener('submit', () => {
                    text.textContent = 'Sending code...';
                    btn.disabled = true;
                });
            }
        </script>


        <form method="POST" action="{{ route('cancel.by.email.verify') }}" class="grid gap-3">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">

            <label class="text-sm text-[var(--muted)]">Verification code</label>
            <input type="text" name="code" placeholder="6-digit code"
                class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base">

            @error('code')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <button type="submit" class="rounded-xl border border-[var(--line)] px-3 py-2">Verify</button>
        </form>

        @if (!empty($verified))
            <div class="mt-6 space-y-3">
                @forelse ($appointments as $appointment)
                    <div
                        class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 shadow-[0_8px_30px_rgba(15,23,42,0.06)]">
                        <p class="text-sm text-[var(--muted)]">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                        <p class="mt-1 font-medium">{{ $appointment->service->name ?? '-' }}</p>

                        <form method="POST" action="{{ route('cancel.by.email.cancel', $appointment) }}"
                            class="mt-4">
                            @csrf
                            <button
                                class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 hover:bg-rose-100">
                                Cancel Appointment
                            </button>
                        </form>

                        <a href="{{ route('cancel.by.email.reschedule.form', $appointment) }}?email={{ $email ?? '' }}"
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
</body>

</html>
