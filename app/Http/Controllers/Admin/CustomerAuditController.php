<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CustomerAuditController extends Controller
{
    public function index()
    {
        $customers = Appointment::select('customer_phone','customer_name')
            ->selectRaw('COUNT(*) as total_visits')
            ->selectRaw('MAX(start_at) as last_visit')
            ->groupBy('customer_phone', 'customer_name')
            ->orderByDesc('last_visit')
            ->get();
        
        return view('admin.customers.index',compact('customers'));
    }

    public function show(string $phone)
    {
        $appointments = Appointment::with('service')
            ->where('customer_phone',$phone)
            ->orderByDesc('start_at')
            ->get();
        
        $customerName = $appointments->first()?->customer_name ?? $phone;

        return view('admin.customers.show',compact('appointments','phone','customerName'));
    }
}
