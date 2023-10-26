<?php

namespace App\Providers;

use App\Models\CourseFile;
use App\Models\CourseVideo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Log\Logger;

class AppServiceProvider extends ServiceProvider
{

    protected $defer = true;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // error_log('register');
        // error_log($this->app === \App);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Relation::MorphMap([
            '0' => CourseVideo::class,
            '1' => CourseFile::class,
            '2' => CourseFile::class,
            '3' => CourseFile::class,
            'any' => CourseFile::class,
        ]);

        // Logger('service');
    }
}
