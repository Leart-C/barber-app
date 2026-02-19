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

    <div class="mx-auto max-w-4xl px-6 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Admin</p>
                <h1 class="mt-2 text-2xl font-semibold">Price List</h1>
            </div>
            <a href="{{ route('admin.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm shadow-sm hover:bg-[var(--accent-soft)]">
                Admin Home
            </a>
        </div>

        {{-- Add new service --}}
        <form method="POST" action="{{ route('admin.services.store') }}"
            class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3 grid gap-3 sm:grid-cols-4">
            @csrf
            <input type="text" name="name" placeholder="Service name"
                class="rounded-xl border border-[var(--line)] px-3 py-2 text-sm">

            <select name="duration_minutes" class="rounded-xl border border-[var(--line)] px-3 py-2 text-sm">
                @foreach ([15, 20, 30, 45, 60, 75, 90] as $m)
                    <option value="{{ $m }}">{{ $m }} min</option>
                @endforeach
            </select>

            <input type="number" name="price_eur" step="0.01" min="0" placeholder="€0.00"
                class="rounded-xl border border-[var(--line)] px-3 py-2 text-sm">

            <button
                class="rounded-xl bg-[var(--accent)] px-3 py-2 text-sm text-white hover:opacity-90">
                Add
            </button>
        </form>

        {{-- Existing services --}}
        <div class="space-y-3">
            @foreach ($services as $service)
                <div class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-3">
                    <div class="grid gap-2 sm:grid-cols-4 sm:items-center">
                        <input type="text" value="{{ $service->name }}" readonly
                            class="rounded-xl border border-[var(--line)] bg-slate-50 px-3 py-2 text-sm">

                        <input type="text" value="{{ $service->duration_minutes }} min" readonly
                            class="rounded-xl border border-[var(--line)] bg-slate-50 px-3 py-2 text-sm">

                        <input type="text" value="{{ number_format($service->price_cents / 100, 2, '.', '') }}" readonly
                            class="rounded-xl border border-[var(--line)] bg-slate-50 px-3 py-2 text-sm">

                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                            class="sm:col-span-1 sm:justify-self-end">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full rounded-xl border border-[var(--danger-border)] bg-[var(--danger-bg)] px-4 py-2 text-sm text-[var(--danger-text)] hover:opacity-90">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
