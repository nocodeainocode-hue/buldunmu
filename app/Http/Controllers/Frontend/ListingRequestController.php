<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\District;
use App\Models\ListingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingRequestController extends Controller
{
    public function create()
    {
        return $this->form();
    }

    public function claim(Company $company)
    {
        abort_unless(app()->bound('currentDirectory'), 404);

        $directory = app('currentDirectory');

        // Shared discovery records are visible on every directory. Claiming one creates
        // a directory-specific profile after approval; an owned record can only be
        // claimed from its own directory.
        abort_unless($company->directory_id === null || (int) $company->directory_id === (int) $directory->id, 404);

        return $this->form($company);
    }

    private function form(?Company $claimCompany = null)
    {
        $categories = Category::active()->orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $districts = District::orderBy('name')->get(['id', 'city_id', 'name']);

        return view('frontend.listing.create', compact('categories', 'cities', 'districts', 'claimCompany'));
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
            'claim_company_id' => 'nullable|integer',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query
                        ->whereNull('directory_id')
                        ->orWhere('directory_id', $directory->id)
                ),
            ],
            'city_id' => [
                'required',
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

        $claimCompany = null;

        if (! empty($validated['claim_company_id'])) {
            $claimCompany = Company::withoutGlobalScope('directory')
                ->active()
                ->find($validated['claim_company_id']);

            abort_unless(
                $claimCompany && ($claimCompany->directory_id === null || (int) $claimCompany->directory_id === (int) $directory->id),
                404
            );
        }

        $validated['directory_id'] = $directory->id;
        $validated['status'] = 'new';

        ListingRequest::create($validated);

        if ($claimCompany) {
            return redirect()->route('companies.claim', $claimCompany->slug)->with('success', 'Profil sahiplenme talebiniz alındı. İnceleme sonrası bilgileriniz güncellenecektir.');
        }

        return redirect()->route('listing.create')->with('success', 'Firma ekleme talebiniz başarıyla gönderildi. İncelendikten sonra size dönüş yapılacaktır.');
    }
}
