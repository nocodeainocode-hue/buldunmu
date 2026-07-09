<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;


class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        $companies = collect();

        if ($q) {
            $companies = Company::active()
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('short_description', 'like', "%{$q}%")
                        ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('city', fn($c) => $c->where('name', 'like', "%{$q}%"));
                })
                ->with(['category', 'city'])
                ->orderByDesc('is_premium')
                ->orderByDesc('created_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('frontend.companies.index', [
            'companies' => $companies,
            'categories' => \App\Models\Category::active()->orderBy('name')->get(),
            'cities' => \App\Models\City::orderBy('name')->get(),
            'metaTitle' => $q ? "Arama: {$q}" : 'Firmalar',
        ]);
    }
}
