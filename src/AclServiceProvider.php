<?php
namespace Silveton\Acl;

use Illuminate\Support\ServiceProvider;

use Illuminate\Filesystem\Filesystem;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__.'/../config/acl.php' => config_path('acl.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_permission_table.php.stub' => $this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.date('Y_m_d_Hi').'1_create_permission_table.php',
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/create_role_table.php.stub' => $this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.date('Y_m_d_Hi').'2_create_role_table.php',
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/create_role_permission_table.php.stub' => $this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.date('Y_m_d_Hi').'3_create_role_permission_table.php',
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'acl');
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/acl.php', 'acl');
    }


}