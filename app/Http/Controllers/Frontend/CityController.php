<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\District;
use Illuminate\Http\Request;


class CityController extends Controller
{
    public function show(string $slug, Request $request)
    {
        $city = City::where('slug', $slug)->firstOrFail();
        $districts = District::where('city_id', $city->id)->orderBy('name')->get();

        $query = Company::active()->where('city_id', $city->id)->with(['category', 'district']);

        if ($request->filled('district')) {
            $query->whereHas('district', fn($d) => $d->where('slug', $request->district));
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($c) => $c->where('slug', $request->category));
        }

        $companies = $query->orderByDesc('is_premium')->orderByDesc('created_at')->paginate(12);

        return view('frontend.cities.show', compact('city', 'companies', 'districts'));
    }
}
