<?php

namespace Sedehi\Section\Console;

use Illuminate\Console\Command;
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
    }

    public function registerMigrations(){

        $appServiceProviderPath = app_path('Providers/AppServiceProvider.php');
        if(!Str::contains(file_get_contents($appServiceProviderPath), 'loadMigration')) {
            $lines              = file($appServiceProviderPath);
            $appServiceProvider = '';
            $linePointer        = null;
            foreach($lines as $lineNumber => $line) {
                $appServiceProvider .= $line;
                if(Str::contains($line, 'boot()')) {
                    if(Str::contains($line, '{')) {
                        $appServiceProvider .= "\n";
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
            $appServiceProvider = substr_replace($appServiceProvider, "\n".file_get_contents(__DIR__.'/stubs/serviceprovider-methods.stub'), strrpos($appServiceProvider, '}') - 1, 0);
            file_put_contents(app_path('Providers/AppServiceProvider.php'), $appServiceProvider);

            $this->info('Section scaffolding installed successfully.');
        }
    }

    private function migrationsLoadCode(){

        return '        if ($this->app->runningInConsole()) {
            $this->loadMigration();
        }'."\n";
    }
}
