<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings</title>
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0f172a">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Barber">
</head>
<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    @include('partials.toast')

    <div class="mx-auto w-full max-w-lg px-4 py-8 sm:py-12">
        <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Booking</p>
        <h1 class="mt-3 text-3xl font-semibold">My Bookings</h1>
        <p class="mt-2 text-sm text-[var(--muted)]">Enter your email to get a code.</p>

        @if (!session('email'))
            <form method="POST" action="{{ route('bookings.send') }}" class="mt-6 grid gap-4">
                @csrf
                <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                    Email
                    <input type="email" name="email"
                        class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base"
                        placeholder="you@example.com">
                </label>
                <button type="submit"
                    class="mt-2 inline-flex items-center justify-center rounded-2xl bg-[var(--accent)] px-4 py-3 text-base text-white shadow hover:opacity-90">
                    Send Code
                </button>
            </form>
        @else
            <form action="{{ route('bookings.verify') }}" method="POST" class="mt-6 grid gap-4">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">
                <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                    Verification code
                    <input type="text" name="code"
                        class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base"
                        placeholder="6-digit code">
                </label>
                <button type="submit"
                    class="mt-2 inline-flex items-center justify-center rounded-2xl bg-[var(--accent)] px-4 py-3 text-base text-white shadow hover:opacity-90">
                    Verify
                </button>
            </form>
        @endif

        @if ($verified ?? false)
            @if ($appointments->isEmpty())
                <p class="mt-4 text-sm text-[var(--muted)]">No upcoming bookings.</p>
            @else
                <div class="mt-4 grid gap-3">
                    @foreach ($appointments as $appointment)
                        <div class="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
                            <p>Start time: {{ $appointment->start_at?->format('Y-m-d H:i') }}</p>
                            <p>Service: {{ $appointment->service->name ?? 'Service' }}</p>
                            <p>Status: {{ $appointment->status }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5 text-sm text-[var(--muted)] text-center">
            <a href="{{ route('booking') }}" class="underline text-[var(--ink)]">Back to Booking</a>
        </div>
    </div>
</body>
</html>
