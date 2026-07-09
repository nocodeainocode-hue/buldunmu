<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;


class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $companies = Company::active()
            ->where('category_id', $category->id)
            ->with(['city', 'district'])
            ->orderByDesc('is_premium')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('frontend.categories.show', compact('category', 'companies'));
    }
}
