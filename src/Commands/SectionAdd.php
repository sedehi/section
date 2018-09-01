<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Console\DetectsApplicationNamespace;

class SectionAdd extends Command
{

    use DetectsApplicationNamespace, SectionsTrait;
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'section:make {name : The name of the sections}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create a new section ';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(){

        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle(){

        $adminController = $siteController = $apiController = false;
        if($this->confirm('Do you want create model ? [y|n]', true)) {
            $this->makeModel();
        }
        if($this->confirm('Do you want create admin controller ? [y|n]', true)) {
            $adminController = true;
            if($this->confirm('Do you want upload picture in admin ? [y|n]', true)) {
                $this->makeAdminControllerWithUpload();
            }else {
                $this->makeAdminController();
            }
        }
        if($this->confirm('Do you want create site controller ? [y|n]', true)) {
            $siteController = true;
            $this->makeSiteController();
        }
        if($this->confirm('Do you want create api controller ? [y|n]', true)) {
            $apiController = true;
            $this->makeApiController();
        }
        if($this->confirm('Do you want create form request ? [y|n]', true)) {
            $this->makeRequest($adminController, $siteController);
        }
        if($this->confirm('Do you want create factory ? [y|n]', true)) {
            $this->makeFactory();
        }
        if($this->confirm('Do you want create migration ? [y|n]', true)) {
           $name =  $this->ask('What is table name?');
            if(empty($name)) {
                $name = $this->argument('name');
            }
            $this->makeMigration($name);
        }
        $title = $this->ask('What is section title?');
        if(empty($title)) {
            $title = $this->argument('name');
        }
        if($this->confirm('Do you want create menu ? [y|n]', true)) {
            $this->makeMenu($title);
        }
        if($this->confirm('Do you want create role ? [y|n]', true)) {
            $this->makeRole($title);
        }
        if($this->confirm('Do you want create route ? [y|n]', true)) {
            $this->makeRoute($adminController, $siteController, $apiController);
        }
    }

    private function makeModel(){

        $this->call('section:model', ['section' => $this->argument('name'), 'name' => $this->argument('name')]);
    }

    private function makeAdminController(){

        $this->call('section:controller', [
            'section'    => $this->argument('name'),
            'name'       => ucfirst($this->argument('name')).'Controller',
            '--admin'    => true,
            '--crud'     => true,
            '--resource' => true,
        ]);
        $this->call('section:view', [
            'section'    => $this->argument('name'),
            'name'       => strtolower($this->argument('name')),
            'controller' => ucfirst($this->argument('name')).'Controller'
        ]);
    }

    private function makeAdminControllerWithUpload(){

        $this->call('section:controller', [
            'section'    => $this->argument('name'),
            'name'       => ucfirst($this->argument('name')).'Controller',
            '--upload'   => true,
            '--resource' => true,
            '--admin'    => true,
        ]);
        $this->call('section:view', [
            'section'    => $this->argument('name'),
            'name'       => strtolower($this->argument('name')),
            'controller' => ucfirst($this->argument('name')).'Controller',
            '--upload'   => true
        ]);
    }

    private function makeSiteController(){

        $this->call('section:controller', [
            'section' => ucfirst($this->argument('name')),
            'name'    => ucfirst($this->argument('name')).'Controller',
            '--site'  => true
        ]);
        if(!File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/site/'))) {

            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/site/'), 0775, true);
        }
    }

    private function makeMigration($name){

        $this->call('section:migration', [
            'section' => ucfirst($this->argument('name')),
            'name'    => 'create_'.$name.'_table',
        ]);

    }

    private function makeApiController(){

        $this->call('section:controller', [
            'section' => ucfirst($this->argument('name')),
            'name'    => ucfirst($this->argument('name')).'Controller',
            '--api'   => true,
            '--v'     => 'v1'
        ]);
    }

    private function makeRequest($adminController, $siteController){

        if($adminController) {
            $this->call('section:request', [
                'section' => $this->argument('name'),
                'name'    => ucfirst($this->argument('name')).'Request',
                '--admin' => true,
                '--crud'  => true,
            ]);
        }
        if($siteController) {
            $this->call('section:request', [
                'section' => $this->argument('name'),
                'name'    => ucfirst($this->argument('name')).'Request',
                '--site'  => true,
                '--crud'  => true,
            ]);
        }
    }

    private function makeMenu($title){

        $this->makeDirectory($this->argument('name'));
        if(File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/menu.php'))) {
            $this->error('menu already exists.');
        }else {
            $data = File::get(__DIR__.'/Template/menu');
            $data = str_replace('{{{title}}}', $title, $data);
            $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{Classname}}}', ucfirst($this->argument('name')), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/menu.php'), $data);
            $this->info('Menu created successfully.');
        }
    }

    private function makeRole($title){

        $this->makeDirectory($this->argument('name'));
        if(File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/roles.php'))) {
            $this->error('roles already exists.');
        }else {
            $data = File::get(__DIR__.'/Template/roles');
            $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{ucFirstname}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{title}}}', $title, $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/roles.php'), $data);
            $this->info('role created successfully.');
        }
    }

    private function makeRoute($adminController, $siteController, $apiController){

        $this->makeDirectory($this->argument('name'), 'routes');
        if($adminController || $siteController) {
            if(File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'web.php'))) {
                $this->error('routes already exists.');
            }else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'web.php'), '<?php ');
                $data = File::get(__DIR__.'/Template/routeAdmin');
                $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
                $data = str_replace('{{{controller}}}', ucfirst($this->argument('name')).'Controller', $data);
                $data = str_replace('{{{url}}}', strtolower($this->argument('name')), $data);
                File::append(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'web.php'), $data);
                $this->info('routes created successfully.');
            }
        }
        if($apiController) {
            if(File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'api.php'))) {
                $this->error('api route already exists.');
            }else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'api.php'), '<?php ');
                $this->info('api route created successfully.');
            }
        }
    }

    private function makeFactory()
    {
        $this->call('section:factory', [
            'section' => ucfirst($this->argument('name')),
        ]);
    }

}
