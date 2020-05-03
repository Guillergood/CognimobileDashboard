<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);

        Response::macro('attachment', function ($name,$content) {

            $headers = [
                'Content-type'        => 'application/json',
                'Content-Disposition' => 'attachment; filename='.$name.'.json',
            ];

            return Response::make($content, 200, $headers);

        });
    }


}
