<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Services</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    @include('partials.toast')
    <div class="mx-auto max-w-4xl px-6 py-10">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Price List</h1>
            <a href="{{ route('admin.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
                Admin Home
            </a>
        </div>
        
        {{-- Add new service --}}
        <form method="POST" action="{{ route('admin.services.store') }}" class="grid gap-3 mb-6 sm:grid-cols-4">
            @csrf
            <input type="text" name="name" placeholder="Service name"
                class="rounded-xl border border-[var(--line)] px-3 py-2">

            <select name="duration_minutes" class="rounded-xl border border-[var(--line)] px-3 py-2">
                @foreach ([15, 20, 30, 45, 60, 75, 90] as $m)
                    <option value="{{ $m }}">{{ $m }} min</option>
                @endforeach
            </select>

            <input type="number" name="price_eur" step="0.01" min="0" placeholder="€0.00"
                class="rounded-xl border border-[var(--line)] px-3 py-2">

            <button class="rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Add</button>
        </form>

        {{-- Existing services --}}
        <div class="space-y-3">
            @foreach ($services as $service)
                <div
                    class="grid gap-3 rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 sm:grid-cols-4 sm:items-center">
                    <form method="POST" action="{{ route('admin.services.update', $service) }}" class="contents">
                        @csrf
                        @method('PATCH')

                        <input type="text" name="name" value="{{ $service->name }}"
                            class="rounded-xl border border-[var(--line)] px-3 py-2">

                        <select name="duration_minutes" class="rounded-xl border border-[var(--line)] px-3 py-2">
                            @foreach ([15, 20, 30, 45, 60, 75, 90] as $m)
                                <option value="{{ $m }}" @selected($service->duration_minutes == $m)>{{ $m }} min
                                </option>
                            @endforeach
                        </select>

                        <input type="number" name="price_eur" step="0.01" min="0"
                            value="{{ number_format($service->price_cents / 100, 2, '.', '') }}"
                            class="rounded-xl border border-[var(--line)] px-3 py-2">

                        <button
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700 hover:bg-emerald-100">
                            Save
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="sm:col-span-4">
                        @csrf
                        @method('DELETE')
                        <button
                            class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 hover:bg-rose-100">
                            Delete
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
