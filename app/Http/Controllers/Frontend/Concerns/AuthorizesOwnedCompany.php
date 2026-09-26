<?php

namespace App\Http\Controllers\Frontend\Concerns;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

trait AuthorizesOwnedCompany
{
    protected function authorizeOwnedCompany(Company $company): void
    {
        abort_unless(app()->bound('currentDirectory'), 404);

        $directory = app('currentDirectory');
        abort_unless(
            (int) $company->directory_id === (int) $directory->id
            && $company->owners()
                ->where('user_id', Auth::id())
                ->wherePivot('directory_id', $directory->id)
                ->wherePivot('status', 'active')
                ->exists(),
            403
        );
    }

    protected function authorizePremiumCompany(Company $company): void
    {
        $this->authorizeOwnedCompany($company);
        abort_unless($company->hasActivePremium(), 403);
    }
}
