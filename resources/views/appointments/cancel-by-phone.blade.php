<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cancel by Phone</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-md px-6 py-10">
        <h1 class="text-2xl font-semibold mb-2">Cancel by Phone</h1>
        <p class="text-slate-600 mb-6">Enter your phone to find your appointments.</p>

        @if (session('message'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('cancel.by.phone.send') }}" class="grid gap-3 mb-4">
            @csrf
            <label class="text-sm">Phone</label>
            <input type="text" name="phone" value="{{ $phone ?? old('phone') }}"
                class="rounded-lg border border-slate-300 px-3 py-2">
            @error('phone')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <button class="rounded-lg bg-slate-900 px-3 py-2 text-white">Send Code</button>
        </form>

        <form method="POST" action="{{ route('cancel.by.phone.verify') }}" class="grid gap-3">
            @csrf
            <input type="hidden" name="phone" value="{{ old('phone', $phone ?? '') }}">
            <label class="text-sm">Verification code</label>
            <input type="text" name="code" class="rounded-lg border border-slate-300 px-3 py-2"
                placeholder="6-digit code">
            @error('code')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <button class="rounded-lg border border-slate-300 px-3 py-2">Verify</button>
        </form>

        @if (!empty($verified))
            <div class="mt-6 space-y-3">
                @forelse ($appointments as $appointment)
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-sm text-slate-600">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                        <p class="font-medium">{{ $appointment->service->name ?? '-' }}</p>

                        <form method="POST" action="{{ route('cancel.by.phone.cancel', $appointment) }}"
                            class="mt-3">
                            @csrf
                            <button
                                class="w-full rounded-lg border border-red-600 px-3 py-2 text-red-700 hover:bg-red-50">
                                Cancel Appointment
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-sm text-slate-500">No upcoming appointments found.</div>
                @endforelse
            </div>
        @endif
    </div>
    @if (session('play_sound'))
        <audio id="notify-sound" src="/sounds/notify.mp3"></audio>
        <script>
            window.addEventListener('load', () => {
                const audio = document.getElementById('notify-sound');
                if (audio) audio.play().catch(() => {});
            });
        </script>
    @endif

</body>

</html>
