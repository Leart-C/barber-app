<?php

use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\AppointmentCancelController;
use App\Http\Controllers\CancelByPhoneController;
use App\Http\Controllers\ProfileController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Booking
Route::get('/', function () {
    return view('booking');
});
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

        $appointments = $query->orderByDesc('start_at')->get();
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





require __DIR__.'/auth.php';
