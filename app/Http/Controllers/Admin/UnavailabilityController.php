<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unavailability;
use Illuminate\Http\Request;

class UnavailabilityController extends Controller
{
    public function index()
    {
        $blocks = Unavailability::orderByDesc('start_at')->get();

        return view('admin.unavailable',compact('blocks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        $data['start_at'] = \Carbon\Carbon::parse($data['start_at'])->toDateTimeString();
        $data['end_at']   = \Carbon\Carbon::parse($data['end_at'])->toDateTimeString();

        Unavailability::create($data);

        return back()->with('message','Unavailable time added');
    }
}
