<?php

namespace G4T\PackageMaker;

use G4T\PackageMaker\Commands\PackageNew;
use G4T\PackageMaker\Commands\PackageRemove;
use Illuminate\Support\ServiceProvider;

class PackageMakerServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../src/config/package-generator.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('package-generator.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PackageNew::class,
                PackageRemove::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'package-generator'
        );
    }

}
