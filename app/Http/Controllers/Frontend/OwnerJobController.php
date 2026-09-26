<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Concerns\AuthorizesOwnedCompany;
use App\Models\Company;
use App\Models\JobPosting;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OwnerJobController extends Controller
{
    use AuthorizesOwnedCompany;

    public function index(Company $company)
    {
        $this->authorizeOwnedCompany($company);

        return view('frontend.owner.jobs', [
            'company' => $company,
            'jobs' => $company->jobPostings()->with('company')->get(),
            'editing' => null,
        ]);
    }

    public function edit(Company $company, JobPosting $job)
    {
        $this->authorizePremiumCompany($company);
        $this->authorizeJob($company, $job);

        return view('frontend.owner.jobs', [
            'company' => $company,
            'jobs' => $company->jobPostings()->with('company')->get(),
            'editing' => $job,
        ]);
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizePremiumCompany($company);
        $data = $this->validatedData($request);
        $data['expires_at'] = filled($data['expires_at'] ?? null)
            ? Carbon::parse($data['expires_at'])->endOfDay()
            : null;
        $data['company_id'] = $company->id;
        $data['directory_id'] = $company->directory_id;
        $data['admin_published'] = false;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $company->jobPostings()->create($data);

        return redirect()->route('owner.jobs.index', $company)->with('success', 'İş ilanı kaydedildi.');
    }

    public function update(Request $request, Company $company, JobPosting $job)
    {
        $this->authorizePremiumCompany($company);
        $this->authorizeJob($company, $job);
        $data = $this->validatedData($request);
        $data['expires_at'] = filled($data['expires_at'] ?? null)
            ? Carbon::parse($data['expires_at'])->endOfDay()
            : null;
        $data['published_at'] = $data['status'] === 'published'
            ? ($job->published_at ?: now())
            : null;

        $job->update($data);

        return redirect()->route('owner.jobs.index', $company)->with('success', 'İş ilanı güncellendi.');
    }

    public function destroy(Company $company, JobPosting $job)
    {
        $this->authorizePremiumCompany($company);
        $this->authorizeJob($company, $job);
        $job->delete();

        return redirect()->route('owner.jobs.index', $company)->with('success', 'İş ilanı silindi.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:20000'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'internship'])],
            'location' => ['nullable', 'string', 'max:180'],
            'apply_email' => ['nullable', 'required_without:apply_url', 'email', 'max:255'],
            'apply_url' => ['nullable', 'required_without:apply_email', 'url', 'max:500'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);
    }

    private function authorizeJob(Company $company, JobPosting $job): void
    {
        abort_unless(
            (int) $job->company_id === (int) $company->id
            && (int) $job->directory_id === (int) $company->directory_id,
            404
        );
    }
}
