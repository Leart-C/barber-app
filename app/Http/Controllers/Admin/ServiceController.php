<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.services',compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:10', 'max:240'],
            'price_eur' => ['required', 'numeric', 'min:0'],
        ]);

        $data['price_cents'] = (int) round($request->input('price_eur') * 100);
        unset($data['price_eur']);

        Service::create($data);

        return back()->with('message','Service added.');
    }

    public function update(Request $request,Service $service)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:10', 'max:240'],
            'price_eur' => ['required', 'numeric', 'min:0'],
        ]);

        $data['price_cents'] = (int) round($request->input('price_eur') * 100);
        unset($data['price_eur']);

        $service->update($data);

        return back()->with('message','Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('message','Service deleted');
    }
}
