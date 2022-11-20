<?php

namespace Modules\System\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Events\RouteMatched;

use Email;
use Menu;
use Modules\System\Console\Commands\DotenvBackupCommand;
use Modules\System\Console\Commands\DotenvDeleteKeyCommand;
use Modules\System\Console\Commands\DotenvGetBackupsCommand;
use Modules\System\Console\Commands\DotenvGetKeysCommand;
use Modules\System\Console\Commands\DotenvRestoreCommand;
use Modules\System\Console\Commands\DotenvSetKeyCommand;
use Modules\System\DotenvEditor;

class SystemServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'System';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'system';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerCommands();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->registerEmailTemplates();
        $this->loadMenus();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('dotenv-editor', DotenvEditor::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'dotenv-editor',
            'command.dotenv.backup',
            'command.dotenv.deletekey',
            'command.dotenv.getbackups',
            'command.dotenv.getkeys',
            'command.dotenv.restore',
            'command.dotenv.setkey',            
        ];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }


    public function registerEmailTemplates()
    {
        if(FacadesConfig::has("{$this->moduleNameLower}.email_templates")) {
            $templates = FacadesConfig::get("{$this->moduleNameLower}.email_templates");

            if(is_array($templates) && count($templates) > 0) {
                foreach($templates as $name => $data) {
                    if(is_string($name) && !blank($data) && is_array($data)) {
                        Email::addTemplate($name, $data);
                    }
                }
            }
        }
    }    

    public function loadMenus()
    {
        Event::listen(RouteMatched::class, function() {
            $menu_path = module_path($this->moduleName, 'Config/menu.php');

            if(file_exists($menu_path)) {
                $menus = include $menu_path;

                foreach($menus as $menu) {
                    Menu::add($menu);
                }
            }
        });        
    }    

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->bind('command.dotenv.backup', DotenvBackupCommand::class);
        $this->app->bind('command.dotenv.deletekey', DotenvDeleteKeyCommand::class);
        $this->app->bind('command.dotenv.getbackups', DotenvGetBackupsCommand::class);
        $this->app->bind('command.dotenv.getkeys', DotenvGetKeysCommand::class);
        $this->app->bind('command.dotenv.restore', DotenvRestoreCommand::class);
        $this->app->bind('command.dotenv.setkey', DotenvSetKeyCommand::class);

        $this->commands('command.dotenv.backup');
        $this->commands('command.dotenv.deletekey');
        $this->commands('command.dotenv.getbackups');
        $this->commands('command.dotenv.getkeys');
        $this->commands('command.dotenv.restore');
        $this->commands('command.dotenv.setkey');
    }    
}
