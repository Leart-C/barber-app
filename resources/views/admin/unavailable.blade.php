<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Unavailable</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto max-w-xl px-6 py-10">
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Unavailable Times</h1>

            <a href="{{ route('admin.index') }}"
                class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--card)] px-4 py-2 text-sm text-[var(--ink)] shadow-sm hover:bg-[var(--accent-soft)]">
                Admin Home
            </a>
        </div>

        @if (session('message'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.unavailable.store') }}" class="grid gap-3 mb-6">
            @csrf
            <label class="text-sm">Start</label>
            <input type="datetime-local" name="start_at" class="rounded-xl border border-[var(--line)] px-3 py-2">
            @error('start_at')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <label class="text-sm">End</label>
            <input type="datetime-local" name="end_at" class="rounded-xl border border-[var(--line)] px-3 py-2">
            @error('end_at')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <label class="text-sm">Reason (optional)</label>
            <input type="text" name="reason" class="rounded-xl border border-[var(--line)] px-3 py-2">

            <button class="rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Add Unavailable</button>
        </form>

        <div class="space-y-3">
            @forelse ($blocks as $block)
                <div
                    class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-5 shadow-[0_8px_30px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-[var(--muted)]">Unavailable</p>
                        <span class="rounded-full px-2 py-1 text-xs font-medium bg-amber-100 text-amber-700">
                            Blocked
                        </span>
                    </div>

                    <div class="mt-3 space-y-2 text-sm">
                        <div>
                            <p class="text-xs text-[var(--muted)] uppercase tracking-wide">From</p>
                            <p class="text-base font-semibold">
                                {{ \Carbon\Carbon::parse($block->start_at)->format('Y-m-d - H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-[var(--muted)] uppercase tracking-wide">To</p>
                            <p class="text-base font-semibold">
                                {{ \Carbon\Carbon::parse($block->end_at)->format('Y-m-d - H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-[var(--muted)] uppercase tracking-wide">Reason</p>
                            <p class="text-sm">
                                {{ $block->reason ?? 'No reason provided' }}
                            </p>
                        </div>
                    </div>
                </div>

            @empty
                <div class="text-sm text-[var(--muted)]">No blocks yet.</div>
            @endforelse
        </div>
    </div>
</body>

</html>
