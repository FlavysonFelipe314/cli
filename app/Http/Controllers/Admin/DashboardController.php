<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_plans' => Plan::count(),
            'revenue' => Subscription::where('status', 'active')
                ->with('plan')
                ->get()
                ->sum(fn($s) => $s->plan->price),
        ];

        $recentUsers = User::with(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where(function($q) {
                      $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                  })
                  ->latest();
        }, 'subscriptions.plan'])->latest()->take(10)->get();
        
        $recentSubscriptions = Subscription::with(['user', 'plan'])->latest()->take(10)->get();
        $plans = Plan::withCount('subscriptions')->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentSubscriptions', 'plans'));
    }
}
