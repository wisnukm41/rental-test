<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with('rentedBy.user')->where('user_id', auth()->id())->where('is_available',true)->get();

        return Inertia::render('Car/Index', [
            'cars' => $cars,
        ]);
    }

    public function store(StoreCarRequest $request)
    {

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $image->store('images','public');
        
        };

        Car::create([
            'user_id' => auth()->id(),
            'brand' => request('brand'),
            'model' => request('model'),
            'police_number' => request('police_number'),
            'rent_fee' => request('rent_fee'),
            'image' => $imageName ?? null,
        ]);

        return redirect(route('car.index'));
    }

    public function update(StoreCarRequest $request, $id)
    {
        $car = Car::where('id', $id)->firstOrFail();

        $car->update([
            'user_id' => auth()->id(),
            'brand' => request('brand'),
            'model' => request('model'),
            'police_number' => request('police_number'),
            'rent_fee' => request('rent_fee'),
        ]);

        if($request->hasFile('image')){
            if(Storage::exists('public/'.$car->image) and $car->image!= 'images/car.jpeg'){
                Storage::delete('public/'.$car->image);
            };
            $image = $request->file('image');
            $imageName = $image->store('images','public');
            $car->update([
                'image' => $imageName
            ]);
        } ;

        return redirect(route('car.index'));
    }

    public function destroy(Request $request, $id)
    {
        $car = Car::where('id', $id)->firstOrFail();

        $car->is_available = false;

        $car->save();

        return redirect(route('car.index'));
    }
}
