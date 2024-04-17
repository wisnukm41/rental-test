<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentRequest;
use App\Models\Car;
use App\Models\RentCar;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RentController extends Controller
{
    public function index()
    {
        $cars = Car::with('user')->where('is_available', true)->get();

        return Inertia::render('Car/Rent', [
            'cars' => $cars,
        ]);
    }

    public function store(StoreRentRequest $request)
    {

        if(Car::whereId(request('selected'))->whereHas('rentCar',function($query){
            $query->whereBetween('start_date', [request('date_start'), request('date_end')])->orWhereBetween('end_date', [request('date_start'), request('date_end')]);
        })->count() > 0){
            throw ValidationException::withMessages(['date_start' => 'This car is not available on that date', 'date_end' => 'This car is not available on that date']);
        }

        RentCar::create([
            'car_id' => request('selected'),
            'start_date' => request('date_start'),   
            'end_date' => request('date_end'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }
}
