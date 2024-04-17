<?php

namespace App\Http\Controllers;

use App\Models\RentCar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BorrowController extends Controller
{
    public function index()
    {
        $cars = RentCar::with('car')->where('user_id',auth()->id())->whereNull('return_date')->get();

        return Inertia::render('Car/Borrow', [
            'cars' => $cars,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'date_return' =>'required',
        ]);

        RentCar::where('id', request('selected'))->update([
           'return_date' => request('date_return'),
           'total_fee' => request('price'),
        ]);

        return redirect()->back();
    }
}
