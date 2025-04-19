<?php
namespace codetyme\TempPassword;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use codetyme\TempPassword\Auth\TempPassUserProvider;

class TempPassServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load the package's migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish the configuration file
        $this->publishes([
            __DIR__ . '/config/temp-password.php' => config_path('temp-password.php'),
        ], 'config');

        // Register the artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \codetyme\TempPassword\Console\GenerateTempPassCommand::class,
            ]);
        }

        // ✅ Hook into Laravel and replace the user provider at runtime
        $this->app->resolving('auth', function ($authManager, $app) {
            $authManager->provider('eloquent', function ($app, $config) {

                return new TempPassUserProvider(
                    $app['hash'],
                    $config['model']
                );
            });
        });
    }

    public function register()
    {
        // Register the config file
        $this->mergeConfigFrom(
            __DIR__.'/config/temp-password.php', 'temp-password'
        );

        $this->app->singleton('temp-password', function () {
            return new \codetyme\TempPassword\TempPass();
        });
    }
}
