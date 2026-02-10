<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('message') }}
        </div>
    @endif

    @if ($step === 'form')
        <form wire:submit.prevent="submit" class="grid gap-4">
            <label class="grid gap-1 text-sm font-medium">
                Service
                <select wire:model="service_id" class="rounded-lg border border-slate-300 px-3 py-2">
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }} ({{ $service->duration_minutes }} min)
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="grid gap-1 text-sm font-medium">
                Your name
                <input type="text" wire:model="customer_name" class="rounded-lg border border-slate-300 px-3 py-2">
                @error('customer_name')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <label class="grid gap-1 text-sm font-medium">
                Phone
                <input type="text" wire:model="customer_phone" class="rounded-lg border border-slate-300 px-3 py-2">
                @error('customer_phone')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <label class="grid gap-1 text-sm font-medium">
                Date & time
                <input type="datetime-local" wire:model="start_at" class="rounded-lg border border-slate-300 px-3 py-2">
                @error('start_at')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            @if ($suggested_start_at)
                <div class="text-sm text-slate-600">
                    Next available:
                    <button type="button" class="underline" wire:click="$set('start_at', '{{ $suggested_start_at }}')">
                        {{ \Carbon\Carbon::parse($suggested_start_at)->format('Y-m-d H:i') }}
                    </button>
                </div>
            @endif


            <label class="grid gap-1 text-sm font-medium">
                Notes (optional)
                <textarea wire:model="notes" rows="3" class="rounded-lg border border-slate-300 px-3 py-2"></textarea>
            </label>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-white hover:bg-slate-800">
                Book Appointment
            </button>
        </form>
    @else
        <form wire:submit.prevent="verify" class="grid gap-4">
            <label class="grid gap-1 text-sm font-medium">
                Verification code
                <input type="text" wire:model="verification_code"
                    class="rounded-lg border border-slate-300 px-3 py-2" placeholder="6-digit code">
                @error('verification_code')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-white hover:bg-slate-800">
                Verify
            </button>
        </form>
    @endif
</div>
