<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reschedule Appointment</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-md px-6 py-10">
        <h1 class="text-2xl font-semibold mb-2">Reschedule Appointment</h1>

        <div class="rounded-xl border border-slate-200 bg-white p-4 mb-6">
            <p class="text-sm text-slate-600">Current time</p>
            <p class="font-medium">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
            <p class="text-sm text-slate-600 mt-2">Service</p>
            <p class="font-medium">{{ $appointment->service->name ?? '-' }}</p>
        </div>

        <form method="POST" action="{{ route('cancel.by.phone.reschedule.save', $appointment) }}" class="grid gap-3">
            @csrf
            <input type="hidden" name="phone" value="{{ $phone ?? '' }}">

            <label class="text-sm">New date & time</label>
            <input type="datetime-local" name="start_at" value="{{ old('start_at') }}"
                   class="rounded-lg border border-slate-300 px-3 py-2">
            @error('start_at') <div class="text-sm text-red-600">{{ $message }}</div> @enderror

            <button class="rounded-lg bg-slate-900 px-3 py-2 text-white">Save New Time</button>
        </form>
    </div>
</body>
</html>
