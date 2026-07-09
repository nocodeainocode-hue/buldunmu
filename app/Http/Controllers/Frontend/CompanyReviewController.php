<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyReviewController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1500'],
        ]);

        $company->reviews()->create([
            ...$data,
            'directory_id' => $company->directory_id,
            'status' => 'pending',
        ]);

        return back()->with('review_success', 'Yorumunuz alındı. Editör onayından sonra yayınlanacaktır.');
    }
}
