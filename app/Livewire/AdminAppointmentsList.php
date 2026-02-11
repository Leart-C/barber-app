<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class AdminAppointmentsList extends Component
{
    public $lastCount = 0;
    public $initialized = false;
    public $filters = ['status'=>'','date'=>''];

    public $lastSeenId = null;
    public $flashId = null;

    public function mount($filters = []):void
    {
        $this->filters = $filters + ['status'=>'','date'=>''];
    }

    public function render()
    {
        $query = Appointment::with('service');

        if(!empty($this->filters['status'])){
            $query->where('status',$this->filters['status']);
        }

        if(!empty($this->filters['date'])){
            $query->whereDate('start_at',$this->filters['date']);
        }

        $appointments = $query->orderByDesc('created_at')->get();

        $latestId = $appointments->first()?->id;

        if ($this->initialized && $latestId && $this->lastSeenId && $latestId !== $this->lastSeenId) {
            $this->flashId = $latestId;
            $this->dispatch('new-appointment', id: $latestId);
        }

        $this->lastSeenId = $latestId;
        $this->initialized = true;

        return view('livewire.admin-appointments-list',compact('appointments'));
    }
}