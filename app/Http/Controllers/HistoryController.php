<?php

namespace App\Http\Controllers;

use App\Models\RentCar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HistoryController extends Controller
{
    public function index()
    {
        $pastRent = RentCar::with(['car','user'])->where('user_id',auth()->id())->whereNotNull('return_date')->get();
        $pastRented = RentCar::with(['car','user'])->whereHas('car', function($query){
            $query->where('user_id', auth()->id());
        })->whereNotNull('return_date')->get();

        return Inertia::render('Car/History', [
            'pastRent' => $pastRent,
            'pastRented' => $pastRented,
        ]);
    }

}
