<?php

namespace ArtisanApi\Velocity;

use Illuminate\Support\ServiceProvider;
use ArtisanApi\Velocity\Commands\API\APIGeneratorCommand;
use ArtisanApi\Velocity\Commands\Common\ModelGeneratorCommand;
use ArtisanApi\Velocity\Commands\Publish\PublishTemplateCommand;
use ArtisanApi\Velocity\Commands\RollbackGeneratorCommand;

class ArtisanApiVelocityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/velocity.php';

        $this->publishes([
            $configPath => config_path('artisanapi/velocity.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('velocity.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('velocity.publish.templates', function ($app) {
            return new PublishTemplateCommand();
        });

        $this->app->singleton('velocity.model', function ($app) {
            return new ModelGeneratorCommand();
        });

        $this->app->singleton('velocity.rollback', function ($app) {
            return new RollbackGeneratorCommand();
        });

        $this->commands([
            'velocity.api',
            'velocity.publish.templates',
            'velocity.model',
            'velocity.rollback'
        ]);
    }
}
