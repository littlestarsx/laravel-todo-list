<?php

namespace Www;

use Illuminate\Support\ServiceProvider;

class PackageTestProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/packagetest.php' => config_path('packagetest.php'), // 发布配置文件到 laravel 的config 下
        ]);
    }

    public function register()
    {
        $this->app->singleton('packagetest', function () {
            return new PackageTestService();
        });
    }

}
