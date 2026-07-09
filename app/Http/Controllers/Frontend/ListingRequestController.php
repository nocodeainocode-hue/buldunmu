<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\ListingRequest;
use Illuminate\Http\Request;


class ListingRequestController extends Controller
{
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        return view('frontend.listing.create', compact('categories', 'cities'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = 'new';

        ListingRequest::create($validated);

        return redirect()->route('listing.create')->with('success', 'Firma ekleme talebiniz başarıyla gönderildi. İncelendikten sonra size dönüş yapılacaktır.');
    }
}
