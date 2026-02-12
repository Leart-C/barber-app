<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('message') }}
        </div>
    @endif

    @if ($step === 'form')
        <form wire:submit.prevent="submit" class="grid gap-4">
            <label class="grid gap-1 text-sm font-medium">
                Service
                <select wire:model.live="service_id" class="rounded-xl border border-[var(--line)] px-3 py-2">
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }} ({{ $service->duration_minutes }} min) —
                            ${{ number_format($service->price_cents / 100, 2) }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="grid gap-1 text-sm font-medium">
                Your name
                <input type="text" wire:model="customer_name" class="rounded-xl border border-[var(--line)] px-3 py-2">
                @error('customer_name')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <label class="grid gap-1 text-sm font-medium">
                Phone
                <div class="flex gap-2">
                    <select wire:model="country_code" class="w-30 rounded-xl border border-[var(--line)] px-2 py-2">
                        <option value="+383">+383 (Kosovo)</option>
                        <option value="+355">+355 (Albania)</option>
                        <option value="+389">+389 (North Macedonia)</option>
                        <option value="+39">+39 (Italy)</option>
                        <option value="+49">+49 (Germany)</option>
                    </select>
                    <input type="text" wire:model="phone_local" placeholder="44..."
                        class="flex-1 rounded-xl border border-[var(--line)] px-3 py-2">
                </div>
                @error('country_code')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
                @error('phone_local')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>


            <label class="grid gap-1 text-sm font-medium">
                Date
                <input type="date" wire:model="selected_date"
                    class="rounded-xl border border-[var(--line)] px-3 py-2">
                @error('selected_date')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <label class="grid gap-2 text-sm font-medium">
                Time Slots
                <div class="grid grid-cols-3 gap-2">
                    @forelse ($available_slots as $slot)
                        <button type="button" wire:click="$set('selected_slot', '{{ $slot }}')"
                            class="rounded-xl border px-2 py-2 text-sm
                    @if ($selected_slot === $slot) bg-[var(--accent)] text-white border-[var(--accent)]
                    @else
                        border-[var(--line)] bg-white text-[var(--ink)] @endif">
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
                        wire:click="
                            $set('selected_date', '{{ \Carbon\Carbon::parse($suggested_start_at)->toDateString() }}');
                            $set('selected_slot', '{{ \Carbon\Carbon::parse($suggested_start_at)->format('H:i') }}')
                        ">
                        {{ \Carbon\Carbon::parse($suggested_start_at)->format('Y-m-d H:i') }}
                    </button>
                </div>
            @endif

            <label class="grid gap-1 text-sm font-medium">
                Notes (optional)
                <textarea wire:model="notes" rows="3" class="rounded-xl border border-[var(--line)] px-3 py-2"></textarea>
            </label>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-3 text-white hover:opacity-90">
                Book Appointment
            </button>
        </form>
    @else
        <form wire:submit.prevent="verify" class="grid gap-4">
            <label class="grid gap-1 text-sm font-medium">
                Verification code
                <input type="text" wire:model="verification_code"
                    class="rounded-xl border border-[var(--line)] px-3 py-2" placeholder="6-digit code">
                @error('verification_code')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </label>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-3 text-white hover:opacity-90">
                Verify
            </button>
        </form>
    @endif
</div>
