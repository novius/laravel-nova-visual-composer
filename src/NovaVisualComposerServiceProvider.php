<?php

namespace Novius\NovaVisualComposer;

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Novius\NovaVisualComposer\Console\Commands\PurgeTmpFiles;

class NovaVisualComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            if (is_file(resource_path('js/vendor/nova-visual-composer/config.js'))) {
                Nova::script('nova-visual-composer-config', resource_path('js/vendor/nova-visual-composer/config.js'));
            } else {
                Nova::script('nova-visual-composer-config', __DIR__.'/../resources/js/config.js');
            }
            Nova::script('nova-visual-composer', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-visual-composer', __DIR__.'/../dist/css/field.css');
            Nova::style('nova-visual-composer-filepond', __DIR__.'/../dist/css/filepond.css');
        });

        $this->publishes([__DIR__.'/../config' => config_path()], 'config');

        $this->publishes([
            __DIR__.'/../resources/js/config.js' => resource_path('js/vendor/nova-visual-composer/config.js')
        ], 'js');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova-visual-composer');
        $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang/vendor/nova-visual-composer')], 'lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-visual-composer');
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/nova-visual-composer')], 'views');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeTmpFiles::class,
            ]);
        }
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-visual-composer')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-visual-composer.php',
            'nova-visual-composer'
        );
    }
}
