<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Appointments</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-4xl px-6 py-10">
        <h1 class="text-2xl font-semibold mb-6">Upcoming Appointments</h1>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Phone</th>
                        <th class="px-4 py-3 text-left">Service</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $appointment->start_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">{{ $appointment->customer_name }}</td>
                            <td class="px-4 py-3">{{ $appointment->customer_phone }}</td>
                            <td class="px-4 py-3">{{ $appointment->service->name ?? '-' }}</td>
                            <td class="px-4 py-3 capitalize">{{ $appointment->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3 text-slate-500" colspan="5">No appointments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
