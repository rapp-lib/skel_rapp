<?php
namespace R\App\Service\App;
use R\Lib\Core\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->config["app.switch.new_table"] = true;
        app()->singleton("security", 'R\App\Service\App\Security');
    }
    public function boot()
    {
        app("table.features")->registerProvider('\R\App\Service\App\AppTableFeatureProvider');
    }
}
