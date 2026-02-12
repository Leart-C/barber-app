<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();

        $sum = fn($from,$to = null)=>Appointment::with('service')
            ->where('status','done')
            ->whereBetween('start_at',[$from,$to ?? Carbon::now()])
            ->sum('price_cents');
        
        $rentEur = (float) Setting::get('monthly_rent_eur',0);

        return view('admin.revenue',[
            'today' => $sum($today),
            'week' => $sum($weekStart),
            'month' => $sum($monthStart),
            'year' => $sum($yearStart),
            'rentEur' => $rentEur,
        ]);
    }

    public function updateRent(Request $request)
    {
        $data = $request->validate([
            'monthly_rent_eur' => ['required', 'numeric', 'min:0'],
        ]);

        Setting::set('monthly_rent_eur', $data['monthly_rent_eur']);

        return back()->with('message','Monthly rent updated.');
    }
}
