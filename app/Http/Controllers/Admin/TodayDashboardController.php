<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class TodayDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $todayAppointments = Appointment::with('service')
            ->whereDate('start_at',$today)
            ->orderBy('start_at')
            ->get();
        
        $stats = [
            'total' => $todayAppointments->count(),
            'booked' => $todayAppointments->where('status', 'booked')->count(),
            'pending' => $todayAppointments->where('status', 'pending')->count(),
            'done' => $todayAppointments->where('status', 'done')->count(),
            'canceled' => $todayAppointments->where('status', 'canceled')->count(),
        ];

        return view('admin.today',compact('todayAppointments','stats'));
    }
}
