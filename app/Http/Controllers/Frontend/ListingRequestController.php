<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\ListingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        abort_unless(app()->bound('currentDirectory'), 404);

        $directory = app('currentDirectory');

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query
                        ->whereNull('directory_id')
                        ->orWhere('directory_id', $directory->id)
                ),
            ],
            'city_id' => [
                'nullable',
                Rule::exists('cities', 'id')->where(
                    fn ($query) => $query
                        ->whereNull('directory_id')
                        ->orWhere('directory_id', $directory->id)
                ),
            ],
            'district_id' => [
                'nullable',
                Rule::exists('districts', 'id')->where(function ($query) use ($request, $directory) {
                    $query->where('city_id', $request->input('city_id'))
                        ->where(function ($directoryQuery) use ($directory) {
                            $directoryQuery
                                ->whereNull('directory_id')
                                ->orWhere('directory_id', $directory->id);
                        });
                }),
            ],
            'message' => 'nullable|string|max:1000',
        ]);

        $validated['directory_id'] = $directory->id;
        $validated['status'] = 'new';

        ListingRequest::create($validated);

        return redirect()->route('listing.create')->with('success', 'Firma ekleme talebiniz başarıyla gönderildi. İncelendikten sonra size dönüş yapılacaktır.');
    }
}
