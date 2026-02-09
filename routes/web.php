<?php

use App\Http\Controllers\ProfileController;
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('booking');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','admin'])->group(function () {

    Route::get('/admin/appointments',function (){
        $appointments = Appointment::with('service')
            ->orderBy('start_at')
            ->get();
        return view('admin.appointments',compact('appointments'));
    })->name('admin.appointments');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
