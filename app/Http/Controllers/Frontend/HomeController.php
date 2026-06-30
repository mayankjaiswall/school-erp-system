<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;

class HomeController extends Controller
{
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::where('status', true)
            ->orderBy('price')
            ->orderBy('duration')
            ->get();

        return view('frontend.home', compact('subscriptionPlans'));
    }
}
