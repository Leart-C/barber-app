<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('message') }}
        </div>
    @endif

    @if ($step === 'form')
        <form wire:submit.prevent="submit" class="grid gap-4">
            <div wire:loading wire:target="submit"
                class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 text-sm">
                Processing your booking… please wait
            </div>

            <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                Service
                <select wire:model.live="service_id"
                    class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base">
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }} ({{ $service->duration_minutes }} min) —
                            €{{ number_format($service->price_cents / 100, 2) }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                Your name
                <input type="text" wire:model="customer_name"
                    class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base">
                @error('customer_name')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                Email
                <input type="email" wire:model="customer_email"
                    class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base">
                @error('customer_email')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <div class="grid gap-2">
                <label class="text-sm text-[var(--muted)] font-medium">Phone</label>

                <select wire:model="country_code"
                    class="w-full rounded-xl border border-[var(--line)] px-3 py-3 text-base">
                    <option value="+383">+383 (Kosovo)</option>
                </select>

                <input type="text" wire:model="phone_local"
                    class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base"
                    placeholder="44xxxxxx">

                @error('phone_local')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                Date
                <input type="date" wire:model.live="selected_date"
                    class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base">
                @error('selected_date')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <label class="grid gap-2 text-sm text-[var(--muted)] font-medium">
                Time Slots
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @forelse ($available_slots as $slot)
                        <button type="button" wire:click="$set('selected_slot', '{{ $slot }}')"
                            class="rounded-xl border px-3 py-3 text-sm
                            @if ($selected_slot === $slot) bg-[var(--accent)] text-white border-[var(--accent)]
                            @else border-[var(--line)] bg-white text-[var(--ink)] @endif">
                            {{ $slot }}
                        </button>
                    @empty
                        <div class="col-span-3 text-sm text-[var(--muted)]">
                            No slots available for this date.
                        </div>
                    @endforelse
                </div>
                @error('selected_slot')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            @if ($suggested_start_at)
                <div class="text-sm text-[var(--muted)]">
                    Next available:
                    <button type="button" class="underline"
                        wire:click="$set('selected_date', '{{ \Carbon\Carbon::parse($suggested_start_at)->toDateString() }}'); $set('selected_slot', '{{ \Carbon\Carbon::parse($suggested_start_at)->format('H:i') }}')">
                        {{ \Carbon\Carbon::parse($suggested_start_at)->format('Y-m-d H:i') }}
                    </button>
                </div>
            @endif

            <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                Notes (optional)
                <textarea wire:model="notes" rows="3" class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base"></textarea>
            </label>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-2xl bg-[var(--accent)] px-4 py-3 text-base text-white shadow hover:opacity-90"
                wire:loading.attr="disabled"
                wire:target="submit">
                <span wire:loading.remove wire:target="submit">Book Appointment</span>
                <span wire:loading wire:target="submit">Sending code…</span>
            </button>

        </form>
    @else
        <form wire:submit.prevent="verify" class="grid gap-4">
            <label class="grid gap-1 text-sm text-[var(--muted)] font-medium">
                Verification code
                <input type="text" wire:model="verification_code"
                    class="w-full rounded-xl border border-[var(--line)] px-4 py-3 text-base"
                    placeholder="6-digit code">
                @error('verification_code')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-2xl bg-[var(--accent)] px-4 py-3 text-base text-white shadow hover:opacity-90">
                Verify
            </button>
        </form>
    @endif
</div>
