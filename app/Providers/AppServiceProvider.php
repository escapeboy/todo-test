<?php

namespace App\Providers;

use App\Models\Lists;
use App\Models\Tasks;
use App\Observers\ListsObserver;
use App\Observers\TasksObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Lists::observe(ListsObserver::class);
        Tasks::observe(TasksObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
