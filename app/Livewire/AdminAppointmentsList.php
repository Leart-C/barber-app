<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class AdminAppointmentsList extends Component
{
    public $lastCount = 0;
    public $initialized = false;
    public $filters = ['status'=>'','date'=>''];

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

        $count = $appointments->count();

        if($this->initialized && $count > $this->lastCount){
            $this->dispatch('new-appointment');
        }

        $this->lastCount = $count;
        $this->initialized = true;

        return view('livewire.admin-appointments-list',compact('appointments'));
    }
}