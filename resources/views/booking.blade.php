<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barber Booking</title>

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0f172a">

    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto w-full max-w-lg px-5 py-10">
        <div class="mb-6 text-center">
            <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Barber Booking</p>
            <h1 class="mt-3 text-3xl font-semibold">Book an Appointment</h1>
            <p class="mt-2 text-sm text-[var(--muted)]">Fast, simple, and confirmed by phone.</p>
        </div>

        <div
            class="rounded-3xl border border-[var(--line)] bg-[var(--card)] p-5 shadow-[0_12px_40px_rgba(15,23,42,0.08)]">
            @livewire('booking-form')
        </div>

        <div class="mt-5 rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 text-sm text-[var(--muted)]">
            Need to change later?
            <a href="{{ route('cancel.by.phone.show') }}" class="underline text-[var(--ink)]">Manage your
                appointment</a>
        </div>
    </div>

    @livewireScripts
    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("/sw.js");
            });
        }
    </script>
</body>

</html>
