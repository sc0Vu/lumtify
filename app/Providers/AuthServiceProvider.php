<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Gate::policy(\App\Article::class, \App\Policies\ArticlePolicy::class);
        Gate::policy(\App\User::class, \App\Policies\UserPolicy::class);
        Gate::policy(\App\Role::class, \App\Policies\RolePolicy::class);
        Gate::policy(\App\Category::class, \App\Policies\CategoryPolicy::class);
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            return User::where('email', $request->input('email'))->first();
        });
    }
}
