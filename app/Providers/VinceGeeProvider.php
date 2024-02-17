<?php

namespace App\Providers;

use App\Models\Masetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Exception;

class VinceGeeProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try{
            $settings = Masetting::findOrFail(1);

            view()->composer('*', function ($view) use ($settings) {
                if (DB::connection()->getDatabaseName()) {
                    if (Schema::hasTable('masettings')) {
                        $view->with([
                            'settings' => $settings
                        ]);
                    }
                }
            });

        } catch(Exception $e){

        }
    }
}
