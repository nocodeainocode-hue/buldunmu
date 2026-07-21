<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\SiteSetting;

class PaketController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        $plans = MembershipPlan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.packages.index', compact('settings', 'directory', 'plans'));
    }
}
