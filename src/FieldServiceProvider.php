<?php

namespace Bythepixel\NovaTinymceField;

use Illuminate\Support\{
    Facades\Route,
    ServiceProvider
};
use Laravel\Nova\{
    Events\ServingNova,
    Nova
};

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-tinymce-field', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-tinymce-field', __DIR__.'/../dist/css/field.css');
        });

        $this->publishes([
            __DIR__.'/../config/nova-tinymce-field.php' => config_path('nova/nova-tinymce-field.php'),
        ], 'config');
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-tinymce-field')
            ->group(__DIR__.'/../routes/api.php');
    }
}
