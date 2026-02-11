<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer History</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-4xl px-6 py-10">
        <a href="{{ route('admin.customers.index') }}" class="text-sm text-slate-600 underline">Back</a>

        <h1 class="text-2xl font-semibold mt-2">{{ $customerName }}</h1>
        <p class="text-slate-600 mb-6">{{ $phone }}</p>

        <div class="space-y-3">
            @forelse ($appointments as $appointment)
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm text-slate-600">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                    <p class="font-medium">{{ $appointment->service->name ?? '-' }}</p>
                    <p class="text-sm text-slate-600">Status: {{ ucfirst($appointment->status) }}</p>
                    <p class="text-sm text-slate-600">Notes: {{ $appointment->notes ?? '—' }}</p>
                </div>
            @empty
                <div class="text-slate-500">No history yet.</div>
            @endforelse
        </div>
    </div>
</body>
</html>
