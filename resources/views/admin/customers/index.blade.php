<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Audit</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-4xl px-6 py-10">
        <h1 class="text-2xl font-semibold mb-6">Customer Audit</h1>

        <div class="space-y-3">
            @forelse ($customers as $customer)
                <a href="{{ route('admin.customers.show', $customer->customer_phone) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium">{{ $customer->customer_name }}</p>
                            <p class="text-sm text-slate-600">{{ $customer->customer_phone }}</p>
                        </div>
                        <div class="text-sm text-slate-600 text-right">
                            <p>Total: {{ $customer->total_visits }}</p>
                            <p>Last: {{ \Carbon\Carbon::parse($customer->last_visit)->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-slate-500">No customers yet.</div>
            @endforelse
        </div>
    </div>
</body>

</html>
