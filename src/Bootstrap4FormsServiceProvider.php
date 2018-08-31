<?php

namespace mantonio84\Bootstrap4Forms;

use Illuminate\Support\ServiceProvider;

class Bootstrap4FormsServiceProvider extends ServiceProvider {

    protected $defer = true;

    public function register()
    {
        $this->app->singleton('bootstrap4-form', function() {
            return new FormService();
        });
    }

    public function provides()
    {
        return ['bootstrap4-form'];
    }

}
