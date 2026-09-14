<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOwner;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OwnerPanelController extends Controller
{
    public function register()
    {
        return view('frontend.owner.register', $this->catalog());
    }

    public function storeRegistration(Request $request)
    {
        $directory = $this->directory();
        $validated = $this->validateCompany($request, $directory->id);
        $validated += $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($validated, $directory): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $company = Company::create([
                'name' => $validated['company_name'],
                'directory_id' => $directory->id,
                'category_id' => $validated['category_id'],
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'email' => $validated['company_email'] ?? null,
                'website' => $validated['website'] ?? null,
                'address' => $validated['address'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'status' => 'pending',
            ]);

            CompanyOwner::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'directory_id' => $directory->id,
                'role' => 'owner',
                'status' => 'active',
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('owner.dashboard')->with('success', 'Firma profiliniz oluşturuldu. Yayına alınmadan önce kısa bir inceleme yapılacaktır.');
    }

    public function login()
    {
        return view('frontend.owner.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'E-posta veya şifre hatalı.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('owner.dashboard'));
    }

    public function dashboard()
    {
        $directory = $this->directory();
        $companies = Auth::user()->ownedCompanies()
            ->wherePivot('directory_id', $directory->id)
            ->with(['category', 'city'])
            ->get();

        return view('frontend.owner.dashboard', compact('directory', 'companies'));
    }

    public function edit(Company $company)
    {
        $this->authorizeCompany($company);

        return view('frontend.owner.edit-company', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeCompany($company);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:1000',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:10000',
            'opening_hours' => 'nullable|string|max:2000',
        ]);

        $company->update($validated);

        return redirect()->route('owner.dashboard')->with('success', 'Firma bilgileriniz kaydedildi.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function catalog(): array
    {
        return [
            'categories' => Category::active()->orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'districts' => District::orderBy('name')->get(['id', 'city_id', 'name']),
        ];
    }

    private function validateCompany(Request $request, int $directoryId): array
    {
        return $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'company_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:1000',
            'short_description' => 'nullable|string|max:500',
            'category_id' => ['required', Rule::exists('categories', 'id')->where(fn ($query) => $query->whereNull('directory_id')->orWhere('directory_id', $directoryId))],
            'city_id' => ['required', Rule::exists('cities', 'id')->where(fn ($query) => $query->whereNull('directory_id')->orWhere('directory_id', $directoryId))],
            'district_id' => ['nullable', Rule::exists('districts', 'id')->where(fn ($query) => $query->where('city_id', $request->input('city_id'))->where(fn ($directoryQuery) => $directoryQuery->whereNull('directory_id')->orWhere('directory_id', $directoryId)))],
        ]);
    }

    private function directory()
    {
        abort_unless(app()->bound('currentDirectory'), 404);

        return app('currentDirectory');
    }

    private function authorizeCompany(Company $company): void
    {
        $directory = $this->directory();

        abort_unless(
            (int) $company->directory_id === (int) $directory->id
            && $company->owners()->where('user_id', Auth::id())->wherePivot('directory_id', $directory->id)->wherePivot('status', 'active')->exists(),
            403
        );
    }
}
