<?php

use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\CustomerAuditController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TodayDashboardController;
use App\Http\Controllers\Admin\UnavailabilityController;
use App\Http\Controllers\AppointmentCancelController;
use App\Http\Controllers\BookingLookupController;
use App\Http\Controllers\CancelByEmailController;
use App\Http\Controllers\CancelByPhoneController;
use App\Http\Controllers\ProfileController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Booking
Route::get('/', function () {
    return view('booking');
})->name('booking');
//Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin (auth + admin)
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/appointments',function (Request $request){
        $query = Appointment::with('service');
        if($request->filled('status')){
            $query->where('status',$request->string('status'));
        }

        if($request->filled('date')){
            $query->whereDate('start_at',$request->date('date'));
        }

        $appointments = $query->orderByDesc('created_at')->get();
         return view('admin.appointments', [
            'appointments' => $appointments,
            'filters' => [
                'status' => $request->string('status')->toString(),
                'date' => $request->string('date')->toString(),
             ],
            ]);
        })->name('admin.appointments');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/admin/appointments/{appointment}/done',[AppointmentStatusController::class,'markDone'])
        ->name('admin.appointments.done');
    Route::patch('/admin/appointments/{appointment}/cancel',[AppointmentStatusController::class,'cancel'])
        ->name('admin.appointments.cancel');
    
    Route::get('/admin/customers',[CustomerAuditController::class,'index'])->name('admin.customers.index');
    Route::get('/admin/customers/{phone}',[CustomerAuditController::class,'show'])->name('admin.customers.show');

    Route::get('/admin/today',[TodayDashboardController::class,'index'])
        ->name('admin.today');

    Route::get('/admin/unavailable',[UnavailabilityController::class,'index'])
        ->name('admin.unavailable');
    
    Route::post('/admin/unavailable',[UnavailabilityController::class,'store'])
        ->name('admin.unavailable.store');
    
    Route::get('/admin', function () {
        return view('admin.index');
        })->name('admin.index');

    Route::get('/admin/services', [ServiceController::class, 'index'])
        ->name('admin.services.index');

    Route::post('/admin/services', [ServiceController::class, 'store'])
        ->name('admin.services.store');

    Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy'])
        ->name('admin.services.destroy');

    Route::get('/admin/revenue',[RevenueController::class,'index'])
        ->name('admin.revenue');

    Route::post('/admin/revenue/rent',[RevenueController::class,'updateRent'])
        ->name('admin.revenue.rent');

    Route::post('/admin/revenue/close-month', [RevenueController::class,'closeMonth'])
        ->name('admin.revenue.close');
    
    Route::get('/admin/revenue/report/{report}',[RevenueController::class,'pdf'])
        ->name('admin.revenue.pdf');

    Route::patch('/admin/unavailable/{unavailability}',[UnavailabilityController::class,'update'])
        ->name('admin.unavailable.update');
    Route::delete('/admin/unavailable/{unavailability}',[UnavailabilityController::class,'destroy'])
        ->name('admin.unavailable.destroy');
});
    
    // Cancel by token (public)
    Route::get('/cancel/{token}',[AppointmentCancelController::class,'show'])
        ->name('appointments.cancel.show');
    Route::post('/cancel/{token}', [AppointmentCancelController::class,'cancel'])
        ->name('appointments.cancel');
    
    // Cancel by phone (public)
    Route::get('/cancel-by-phone', [CancelByPhoneController::class, 'show'])->name('cancel.by.phone.show');
    Route::post('/cancel-by-phone', [CancelByPhoneController::class, 'sendCode'])->name('cancel.by.phone.send');
    Route::post('/cancel-by-phone/verify', [CancelByPhoneController::class, 'verify'])->name('cancel.by.phone.verify');
    Route::post('/cancel-by-phone/{appointment}/cancel', [CancelByPhoneController::class, 'cancel'])
        ->name('cancel.by.phone.cancel');
    Route::get('/cancel-by-phone/verify', function () {
        return redirect()->route('cancel.by.phone.show');
    });

    Route::get('/cancel-by-phone/{appointment}/reschedule', [CancelByPhoneController::class, 'rescheduleForm'])
        ->name('cancel.by.phone.reschedule.form');
    Route::post('/cancel-by-phone/{appointment}/reschedule/save', [CancelByPhoneController::class, 'reschedule'])   
        ->name('cancel.by.phone.reschedule.save');

    //Cancel by email (public)
    Route::get('/cancel-by-email',[CancelByEmailController::class,'show'])
        ->name('cancel.by.email.show');
    Route::post('/cancel-by-email',[CancelByEmailController::class,'sendCode'])
        ->name('cancel.by.email.send');
    Route::post('/cancel-by-email/verify',[CancelByEmailController::class,'verify'])
        ->name('cancel.by.email.verify');
    Route::post('/cancel-by-email/{appointment}/cancel',[CancelByEmailController::class,'cancel'])
        ->name('cancel.by.email.cancel');
    Route::get('/cancel-by-email/{appointment}/reschedule',[CancelByEmailController::class, 'rescheduleForm'])
        ->name('cancel.by.email.reschedule.form');
    Route::post('/cancel-by-email/{appointment}/reschedule/save',[CancelByEmailController::class, 'reschedule'])
        ->name('cancel.by.email.reschedule.save');
    Route::get('/cancel-by-email/verify', function () {
        return redirect()->route('cancel.by.email.show');
    });

    //my bookings
    Route::get('/bookings',function(){
        return view('bookings.lookup');
    })->name('bookings.lookup');

    Route::post('/bookings',[BookingLookupController::class,'sendCode'])
        ->name('bookings.send');
    
    Route::post('/bookings/verify',[BookingLookupController::class,'verifyCode'])
        ->name('bookings.verify');




require __DIR__.'/auth.php';
