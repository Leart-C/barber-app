<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barber Booking</title>

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#111111">

    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-2xl px-6 py-12">
        <div class="mb-8">
            <p class="text-sm uppercase tracking-widest text-slate-500">Barber Booking</p>
            <h1 class="mt-2 text-3xl font-semibold">Book an Appointment</h1>
            <p class="mt-2 text-slate-600">Choose a service and time. We’ll confirm by phone.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @livewire('booking-form')
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
