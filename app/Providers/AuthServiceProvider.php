<?php

namespace App\Providers;

use App\Models\Member;
use App\Models\Plan;
use App\Models\Payment;
use App\Policies\MemberPolicy;
use App\Policies\PlanPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        Member::class => MemberPolicy::class,
        Plan::class => PlanPolicy::class,
        Payment::class => PaymentPolicy::class
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
