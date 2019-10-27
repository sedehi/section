<?php

namespace Sedehi\Section\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'section:install';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Install all of the Section resources';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle(){

        $this->registerMigrations();
        $this->registerRoutes();
        $this->publishViews();
        $this->info('Section scaffolding installed successfully.');
    }

    public function registerMigrations(){

        $appServiceProviderPath = app_path('Providers/AppServiceProvider.php');
        $appServiceProvider     = file_get_contents($appServiceProviderPath);
        $eol                    = $this->EOL($appServiceProvider);
        if(!Str::contains($appServiceProvider, 'loadMigration')) {
            $lines              = file($appServiceProviderPath);
            $appServiceProvider = '';
            $linePointer        = null;
            foreach($lines as $lineNumber => $line) {
                $appServiceProvider .= $line;
                if(Str::contains($line, 'boot()')) {
                    if(Str::contains($line, '{')) {
                        $appServiceProvider .= $eol;
                        $appServiceProvider .= $this->migrationsLoadCode();
                    }else {
                        $linePointer = $lineNumber + 1;
                    }
                }
                if($linePointer === $lineNumber) {

                    $appServiceProvider .= $this->migrationsLoadCode();
                    $linePointer        = null;
                }
            }
            $appServiceProvider = substr_replace($appServiceProvider, $eol.file_get_contents(__DIR__.'/stubs/serviceprovider-methods.stub'), strrpos($appServiceProvider, '}') - 1, 0);
            file_put_contents(app_path('Providers/AppServiceProvider.php'), $appServiceProvider);
        }
    }

    public function registerRoutes(){

        if(!File::exists(base_path('routes/admin.php'))) {
            file_put_contents(base_path('routes/admin.php'), '<?php ');
        }
        $routeServiceProviderPath = app_path('Providers/RouteServiceProvider.php');
        $routeServiceProvider     = file_get_contents($routeServiceProviderPath);
        if(Str::contains($routeServiceProvider, 'mapAdminRoutes')) {
            return;
        }
        $eol = $this->EOL($routeServiceProvider);
        file_put_contents($routeServiceProviderPath, str_replace("->group(base_path('routes/api.php'));", $this->apiRouteCode(), $routeServiceProvider));
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        file_put_contents($routeServiceProviderPath, str_replace("->group(base_path('routes/web.php'));", $this->webRouteCode(), $routeServiceProvider));
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        file_put_contents($routeServiceProviderPath, str_replace('$this->mapWebRoutes();', '$this->mapWebRoutes();'.$eol.'        $this->mapAdminRoutes();', $routeServiceProvider));
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        if(!Str::contains(file_get_contents($routeServiceProviderPath), 'function mapAdminRoutes')) {
            $routeServiceProvider = substr_replace($routeServiceProvider, $eol.$this->adminRouteCode(), strrpos($routeServiceProvider, '}') - 1, 0);
            file_put_contents($routeServiceProviderPath, $routeServiceProvider);
        }
    }

    protected function migrationsLoadCode(){

        return '        if ($this->app->runningInConsole()) {
            $this->loadMigration();
        }'."\n";
    }

    protected function adminRouteCode(){

        return '    protected function mapAdminRoutes(){

        Route::namespace($this->namespace)->middleware(\'admin\')->group(function(){

            $routes = glob(app_path(\'Http/Controllers/*/routes/admin.php\'));
            foreach($routes as $route) {
                require $route;
            }
            require base_path(\'routes/admin.php\');
        });
    }';
    }

    protected function apiRouteCode(){

        return '->group(function () {
            $routes = glob(app_path(\'Http/Controllers/*/routes/api.php\'));
            foreach ($routes as $route) {
                require $route;
            }
            require base_path(\'routes/api.php\');
        });';
    }

    protected function webRouteCode(){

        return '->group(function () {
            $routes = glob(app_path(\'Http/Controllers/*/routes/web.php\'));
            foreach ($routes as $route) {
                require $route;
            }
            require base_path(\'routes/web.php\');
        });';
    }

    protected function EOL(string $routeServiceProvider){

        $lineEndingCount = [
            "\r\n" => substr_count($routeServiceProvider, "\r\n"),
            "\r"   => substr_count($routeServiceProvider, "\r"),
            "\n"   => substr_count($routeServiceProvider, "\n"),
        ];

        return array_keys($lineEndingCount, max($lineEndingCount))[0];
    }

    private function publishViews()
    {
        $this->updateViewConfig();

        $this->call('vendor:publish',[
            '--tag' =>  'section-assets'
        ]);
    }

    private function updateViewConfig()
    {
        $viewConfigPath = config_path('view.php');
        $viewConfig     = file_get_contents($viewConfigPath);
        $eol                    = $this->EOL($viewConfig);
        if(!Str::contains($viewConfig, 'app_path(\'Http/Controllers\')')) {
            $lines      = file($viewConfigPath);
            $viewConfig = '';
            foreach($lines as $lineNumber => $line) {
                $viewConfig .= $line;
                if(Str::contains($line, 'paths')) {
                    if(Str::contains($line, '[')) {
                        $viewConfig .= "\t\t".'app_path(\'Http/Controllers\'),'.$eol;
                    }
                }
            }
            file_put_contents($viewConfigPath, $viewConfig);
        }
    }
}
