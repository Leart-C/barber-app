<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cancel Appointment</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-md px-6 py-10">
        <h1 class="text-2xl font-semibold mb-2">Cancel Appointment</h1>
        <p class="text-slate-600 mb-6">Are you sure you want to cancel this appointment?</p>

        <div class="rounded-xl border border-slate-200 bg-white p-4 mb-6">
            <p><strong>Date:</strong> {{ $appointment->start_at->format('Y-m-d H:i') }}</p>
            <p><strong>Service:</strong> {{ $appointment->service->name ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
        </div>

        <form method="POST" action="{{ route('appointments.cancel', $appointment->cancel_token) }}">
            @csrf
            <button class="w-full rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-500">
                Confirm Cancel
            </button>
        </form>
    </div>
</body>
</html>
