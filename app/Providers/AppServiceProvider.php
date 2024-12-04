<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Mixins\EloquentBuilderMixin;
use App\Mixins\QueryBuilderMixin;
use App\Mixins\ResponseMixin;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->environment('local') || config("app.env_name")  == "DEVSERVER") {
            //\URL::forceScheme('https'); ignores pagination
            $this->app['request']->server->set('HTTPS', 'on');
        }
        Schema::defaultStringLength(191);
        Builder::mixin(new EloquentBuilderMixin());
        \Illuminate\Database\Query\Builder::mixin(new QueryBuilderMixin());
        ResponseFactory::mixin(new ResponseMixin());

        View::composer(['dashboard.bank.campaigns*', 'dashboard.depositor.campaigns*', '*'], function ($view) {
            $user = auth()->user();
            if ($user) {
                $user->assignRoles =  DB::table('roles')
                    ->select([
                        'roles.id',
                        'roles.name',
                        'permissions.name as permission_name'
                    ])
                    ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
                    ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                    ->where('roles.id', $user->role_id)->get();

                $view->with('userLoggedIn', $user);
            }
        });
    }
}
