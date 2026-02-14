<?php

namespace App\Listeners;

use App\Events\MonthClosed;
use App\Models\Appointment;
use App\Models\RevenueReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateMonthlyReport
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MonthClosed $event): void
    {
        $monthStart = $event->monthStart;
        $monthEnd = $event->monthEnd;
        $rentEur = $event->rentEur;
        $rentCents = (int) round($rentEur *100);
        

        $grossCents = Appointment::where('status','done')
            ->whereBetween('done_at',[$monthStart,$monthEnd])
            ->sum('price_cents');

        $netCents = $grossCents - $rentCents;

        $doneCount = Appointment::where('status','done')
            ->whereBetween('done_at',[$monthStart,$monthEnd])
            ->count();
        
        if($doneCount > 0){
            $avgTicketCents = (int) round($grossCents / $doneCount);
        }else{
            $avgTicketCents = 0;
        }

        RevenueReport::create
        (
            [
                'month' => $monthStart->format('Y-m'),
                'gross_cents' => $grossCents,
                'rent_eur'=>$rentEur,
                'net_cents'=>$netCents,
                'done_count'=>$doneCount,
                'avg_ticket_cents'=>$avgTicketCents
            ]
        );
    }
}
