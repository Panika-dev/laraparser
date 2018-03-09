<?php

namespace App\Providers;

use App\Contracts\ParserInterface;
use App\Parsers\TursabParser;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->bind(ParserInterface::class, TursabParser::class);
    }
}
