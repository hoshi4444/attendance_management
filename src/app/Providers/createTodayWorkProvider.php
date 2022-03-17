<?php

namespace App\Providers;

use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class createTodayWorkProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('createTodayWork', function () {
            Log::debug("createTodayWork!");
            Log::debug(Auth::id());
            $has_today_work = Work::where('user_id', Auth::id())
                            ->whereDate('on_date', date('Y/m/d'))
                            ->exists();

            if(!$has_today_work) {
                Log::debug("nothing work..to create");
                Work::create([
                    "user_id" => Auth::id(),
                    "work_type_id" => 1,
                    'on_date' => date('Y/m/d'),
                ]);
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
