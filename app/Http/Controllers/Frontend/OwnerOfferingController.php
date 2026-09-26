<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Concerns\AuthorizesOwnedCompany;
use App\Models\Company;
use App\Models\CompanyOffering;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OwnerOfferingController extends Controller
{
    use AuthorizesOwnedCompany;

    public function index(Company $company)
    {
        $this->authorizeOwnedCompany($company);

        return view('frontend.owner.offerings', [
            'company' => $company,
            'offerings' => $company->offerings()->get(),
            'editing' => null,
        ]);
    }

    public function edit(Company $company, CompanyOffering $offering)
    {
        $this->authorizePremiumCompany($company);
        $this->authorizeOffering($company, $offering);

        return view('frontend.owner.offerings', [
            'company' => $company,
            'offerings' => $company->offerings()->get(),
            'editing' => $offering,
        ]);
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizePremiumCompany($company);
        $data = $this->validatedData($request);
        unset($data['image']);
        $data['sort_order'] ??= 0;
        $data['company_id'] = $company->id;
        $data['directory_id'] = $company->directory_id;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('company-offerings/'.$company->id, 'public');
        }

        $company->offerings()->create($data);

        return redirect()->route('owner.offerings.index', $company)->with('success', 'Vitrin öğesi eklendi.');
    }

    public function update(Request $request, Company $company, CompanyOffering $offering)
    {
        $this->authorizePremiumCompany($company);
        $this->authorizeOffering($company, $offering);
        $data = $this->validatedData($request);
        unset($data['image']);
        $data['sort_order'] ??= 0;

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('company-offerings/'.$company->id, 'public');
            $data['image_path'] = $newPath;
        }

        $offering->update($data);

        return redirect()->route('owner.offerings.index', $company)->with('success', 'Vitrin öğesi güncellendi.');
    }

    public function destroy(Company $company, CompanyOffering $offering)
    {
        $this->authorizePremiumCompany($company);
        $this->authorizeOffering($company, $offering);
        $offering->delete();

        return redirect()->route('owner.offerings.index', $company)->with('success', 'Vitrin öğesi silindi.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(['product', 'service'])],
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:3000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'status' => ['required', Rule::in(['active', 'draft'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function authorizeOffering(Company $company, CompanyOffering $offering): void
    {
        abort_unless(
            (int) $offering->company_id === (int) $company->id
            && (int) $offering->directory_id === (int) $company->directory_id,
            404
        );
    }
}
