<?php

namespace CaLaravel\SampleApi;

use Illuminate\Support\ServiceProvider;

use CaLaravel\SampleApi\Http\Commands\GenerateApiCommand;


class SampleApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap Các dịch vụ ứng dụng.
     */
    public function boot()
    {
        /*
         * Phương pháp tùy chọn để tải tài sản gói của bạn
         */
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sample-api');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'sample-api');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sample-api.php' => config_path('sample-api.php'),
            ], 'config');
           
            // Xuất bản các quan điểm.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/sample-api'),
            ], 'views');*/

            // Xuất bản tài sản.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/sample-api'),
            ], 'assets');*/

            // Xuất bản các tập tin dịch.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/sample-api'),
            ], 'lang');*/

            // Đăng ký lệnh gói.
            $this->commands([
                'sample-api-command'
            ]);
        }
    }

    /**
     * Đăng ký các dịch vụ ứng dụng.
     */
    public function register()
    {
        // Tự động áp dụng cấu hình gói
        $this->mergeConfigFrom(__DIR__.'/../config/sample-api.php', 'sample-api');

        // Đăng ký lớp chính để sử dụng với mặt tiền
        $this->app->singleton('sample-api-command', function () {
            return new GenerateApiCommand;
        });
        
    }
}
