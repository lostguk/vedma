<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Team;
use App\Models\Topic;
use App\Policies\TeamPolicy;
use App\Policies\TopicPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Topic::class => TopicPolicy::class,
    ];

    public function boot(): void {}
}
