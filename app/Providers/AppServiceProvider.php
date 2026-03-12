<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BusinessRequest; 
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  

  public function boot(): void
    {
        // This shares the variable only with your app layout or sidebar
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                // Get count of tasks where the user is the worker and status is not 'completed'
                $taskCount = BusinessRequest::where('worker_id', Auth::id())
                    ->where('status', '!=', 'completed') // Adjust status names as per your DB
                    ->count();

                $view->with('assignedTaskCount', $taskCount);
            }
        });
    }
}
