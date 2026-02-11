<div wire:poll.5s>
    <div class="space-y-4">
        <div
            class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
            @forelse ($appointments as $appointment)
                <div wire:key="appointment-{{ $appointment->id }}"
                    class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-4 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-[var(--muted)]">Date & Time</p>
                            <p class="text-base font-medium">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <span
                            class="rounded-full px-2 py-1 text-xs font-medium
                        @if ($appointment->status === 'booked') bg-blue-100 text-blue-700
                        @elseif ($appointment->status === 'pending') bg-amber-100 text-amber-700
                        @elseif ($appointment->status === 'done') bg-emerald-100 text-emerald-700
                        @elseif ($appointment->status === 'canceled') bg-rose-100 text-rose-700
                        @else bg-slate-100 text-slate-700 @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="mt-3 grid gap-1 text-sm">
                        <p><span class="text-[var(--muted)]">Customer:</span> {{ $appointment->customer_name }}</p>
                        <p><span class="text-[var(--muted)]">Phone:</span> {{ $appointment->customer_phone }}</p>
                        <p><span class="text-[var(--muted)]">Service:</span> {{ $appointment->service->name ?? '-' }}
                        </p>
                    </div>

                    <div class="mt-4 flex gap-3">
                        @if (in_array($appointment->status, ['pending', 'booked']))
                            <form method="POST" action="{{ route('admin.appointments.done', $appointment) }}"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="w-full rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700 hover:bg-emerald-100">
                                    Done
                                </button>
                            </form>
                        @endif

                        @if ($appointment->status !== 'canceled')
                            <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700 hover:bg-rose-100">
                                    Cancel
                                </button>
                            </form>
                        @endif

                        @if (!in_array($appointment->status, ['pending', 'booked']) && $appointment->status === 'canceled')
                            <div class="flex-1 text-center text-xs text-[var(--muted)]">No actions</div>
                        @endif
                    </div>

                    <button type="button"
                        onclick="document.getElementById('details-{{ $appointment->id }}').showModal()"
                        class="mt-3 rounded-xl border border-[var(--line)] px-3 py-2 text-[var(--ink)] hover:bg-[var(--accent-soft)]">
                        Details
                    </button>

                    <dialog wire:ignore.self id="details-{{ $appointment->id }}"
                        class="rounded-2xl border border-[var(--line)] p-0"
                        onclick="if (event.target === this) this.close();">
                        <div class="w-[90vw] max-w-md bg-[var(--card)] p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">Appointment</p>
                                    <h2 class="mt-1 text-lg font-semibold">Details</h2>
                                </div>
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium
                @if ($appointment->status === 'booked') bg-blue-100 text-blue-700
                @elseif ($appointment->status === 'pending') bg-amber-100 text-amber-700
                @elseif ($appointment->status === 'done') bg-emerald-100 text-emerald-700
                @elseif ($appointment->status === 'canceled') bg-rose-100 text-rose-700
                @else bg-slate-100 text-slate-700 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="rounded-xl border border-[var(--line)] bg-[var(--accent-soft)] p-3">
                                    <p class="text-[var(--muted)] text-xs">Time</p>
                                    <p class="font-medium">{{ $appointment->start_at->format('Y-m-d H:i') }}</p>
                                </div>

                                <div class="grid gap-2">
                                    <div class="flex justify-between">
                                        <span class="text-[var(--muted)]">Customer</span>
                                        <span class="font-medium">{{ $appointment->customer_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[var(--muted)]">Phone</span>
                                        <span class="font-medium">{{ $appointment->customer_phone }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-[var(--muted)]">Service</span>
                                        <span class="font-medium">{{ $appointment->service->name ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-[var(--line)] p-3">
                                    <p class="text-[var(--muted)] text-xs">Notes</p>
                                    <p class="mt-1">{{ $appointment->notes ?? '—' }}</p>
                                </div>
                            </div>

                            <form method="dialog" class="mt-5">
                                <button class="w-full rounded-xl bg-[var(--accent)] px-3 py-2 text-white">Close</button>
                            </form>
                        </div>
                    </dialog>

                </div>
            @empty
                <div
                    class="rounded-2xl border border-[var(--line)] bg-[var(--card)] p-6 text-center text-[var(--muted)]">
                    No appointments yet.
                </div>
            @endforelse
        </div>
    </div>
