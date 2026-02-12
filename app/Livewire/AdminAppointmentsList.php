<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;

class AdminAppointmentsList extends Component
{
    use WithPagination;

    public $initialized = false;
    public $filters = ['status' => '', 'date' => ''];

    public $lastSeenId = null;
    public $flashId = null;

    protected $paginationTheme = 'tailwind';

    public $lastCanceledAt = null;
    public $lastRescheduledAt = null;

    public function mount($filters = []): void
    {
        $this->filters = $filters + ['status' => '', 'date' => ''];
    }

    public function render()
    {
        $query = Appointment::with('service');

        $query->whereIn('status', ['pending', 'booked']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['date'])) {
            $query->whereDate('start_at', $this->filters['date']);
        }

        $appointments = $query->orderByDesc('created_at')->paginate(10);

        $latestId = $appointments->first()?->id;
        $currentPage = $appointments->currentPage();

        $latestCanceled = Appointment::whereNotNull('canceled_at')->max('canceled_at');
        $latestRescheduled = Appointment::whereNotNull('rescheduled_at')->max('rescheduled_at');

        if ($this->initialized && $currentPage === 1) {
            if ($latestCanceled && $latestCanceled !== $this->lastCanceledAt) {
                $this->dispatch('appointment-canceled');
            }

            if ($latestRescheduled && $latestRescheduled !== $this->lastRescheduledAt) {
                $this->dispatch('appointment-rescheduled');
            }
        }

        $this->lastCanceledAt = $latestCanceled;
        $this->lastRescheduledAt = $latestRescheduled;

        if ($this->initialized && $currentPage === 1 && $latestId && $this->lastSeenId && $latestId !== $this->lastSeenId) {
            $this->flashId = $latestId;
            $this->dispatch('new-appointment', id: $latestId);
        }

        $this->lastSeenId = $latestId;
        $this->initialized = true;

        return view('livewire.admin-appointments-list', compact('appointments'));
    }

    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function updatingPage()
    {
        $this->initialized = false;
    }
}
