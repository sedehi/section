<?php

namespace Sedehi\Section;

use Faker\Generator as FakerGenerator;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;

class SectionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/admin'),
            ], 'section-views');
            $this->publishes([
                __DIR__.'/../resources/assets/dist' => public_path('assets/admin'),
            ], 'section-assets');
            $this->publishes([
                __DIR__.'/../resources/assets/sass'   => resource_path('assets/sass'),
                __DIR__.'/../resources/assets/js'     => resource_path('assets/js'),
                __DIR__.'/../resources/assets/static' => resource_path('assets/static'),
            ], 'section-assets-sources');
            $this->publishes([
                __DIR__.'/../resources/lang/en/admin.php' => resource_path('lang/en/admin.php'),
                __DIR__.'/../resources/lang/fa/admin.php' => resource_path('lang/fa/admin.php'),
            ], 'section-translations');
        }
        $this->factories();
    }

    public function register()
    {
        $this->app->register(ArtisanServiceProvider::class);
        if (class_exists(Illuminate\Database\MigrationServiceProvider::class)) {
            $this->app->register(MigrationServiceProvider::class);
        }
    }

    protected function factories()
    {
        if (in_array($this->app->environment(), ['local', 'testing'])) {
            $this->app->singleton(EloquentFactory::class, function ($app) {
                return EloquentFactory::construct($app->make(FakerGenerator::class), __DIR__.'/database/factories');
            });
        }
    }
}
