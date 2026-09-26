<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['q' => ['nullable', 'string', 'max:120']]);
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $jobs = JobPosting::visible()
            ->with(['company.city'])
            ->when($directory, fn ($query) => $query->where('directory_id', $directory->id))
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->input('q'));
                $query->where(fn ($search) => $search
                    ->where('title', 'like', "%{$term}%")
                    ->orWhereHas('company', fn ($companies) => $companies->where('name', 'like', "%{$term}%")));
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.jobs.index', compact('jobs', 'directory'));
    }

    public function show(string $slug)
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $job = JobPosting::visible()
            ->with(['company.city'])
            ->when($directory, fn ($query) => $query->where('directory_id', $directory->id))
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.jobs.show', compact('job', 'directory'));
    }
}
