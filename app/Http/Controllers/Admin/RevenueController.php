<?php

namespace App\Http\Controllers\Admin;

use App\Events\MonthClosed;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\RevenueReport;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RevenueController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();
        

        $sum = fn($from,$to = null)=>Appointment::where('status','done')
            ->whereBetween('done_at',[$from,$to ?? Carbon::now()])
            ->sum('price_cents');

        $todayRevenue = $sum($today);
        $todayCount = Appointment::where('status','done')
            ->whereDate('done_at',$today)
            ->count();
        $avgTicket = $todayCount > 0 ? (int) round($todayRevenue / $todayCount) : 0;
        $monthCanceled = Appointment::where('status','canceled')
            ->whereBetween('canceled_at',[$monthStart,Carbon::now()])
            ->count();
        $rentEur = (float) Setting::get('monthly_rent_eur',0);
        $rentCents = (int) round($rentEur * 100);
        $month = $sum($monthStart);
        $netMonth = $month - $rentCents;
        $week = $sum($weekStart);
        $year = $sum($yearStart);

        $reports = RevenueReport::query()
            ->orderBy('month','desc')
            ->limit(6)
            ->get();

        return view('admin.revenue',[
            'today' => $todayRevenue,
            'week' => $week,
            'month' => $month,
            'year' => $year,
            'rentEur' => $rentEur,
            'netMonth'=>$netMonth,
            'todayRevenue'=>$todayRevenue,
            'todayCount'=>$todayCount,
            'avgTicket'=>$avgTicket,
            'monthCanceled'=>$monthCanceled,
            'reports'=>$reports,
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

    public function closeMonth(){
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $monthKey = now()->format('Y-m');
        $rentEur = Setting::get('monthly_rent_eur',0);

        $alreadyClosed = RevenueReport::where('month',$monthKey)->exists();

        if($alreadyClosed) return back()->with('error', 'Monthly already closed');
        
        event(new MonthClosed($monthStart, $monthEnd,(float) $rentEur));

        return back()->with('message', 'Successfully Closed');
    }

    public function pdf(RevenueReport $report){
        $pdf = Pdf::loadView('admin.revenue-pdf',['report'=>$report]);

        return $pdf->download('revenue-' .$report->month.'.pdf');
    }
}
