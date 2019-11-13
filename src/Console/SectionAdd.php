<?php

namespace Sedehi\Section\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SectionAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:section {name : The name of the sections}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new section ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $adminController = $siteController = $apiController = false;
        if ($this->confirm('Do you want create model ? [y|n]', true)) {
            $this->makeModel();
        }
        if ($this->confirm('Do you want create admin controller ? [y|n]', true)) {
            $adminController = true;
            if ($this->confirm('Do you want upload picture in admin ? [y|n]', true)) {
                $this->makeAdminControllerWithUpload();
            } else {
                $this->makeAdminController();
            }
        }
        if ($this->confirm('Do you want create site controller ? [y|n]', true)) {
            $siteController = true;
            $this->makeSiteController();
        }
        if ($this->confirm('Do you want create api controller ? [y|n]', true)) {
            $apiController = true;
            $this->makeApiController();
        }
        if ($this->confirm('Do you want create form request ? [y|n]', true)) {
            $this->makeRequest($adminController, $siteController);
        }
        if ($this->confirm('Do you want create factory ? [y|n]', true)) {
            $this->makeFactory();
        }
        if ($this->confirm('Do you want create migration ? [y|n]', true)) {
            $name = $this->ask('What is table name?');
            if (empty($name)) {
                $name = $this->argument('name');
            }
            $this->makeMigration($name);
        }
        $title = $this->ask('What is section title?');
        if (empty($title)) {
            $title = $this->argument('name');
        }
        if ($this->confirm('Do you want create role ? [y|n]', true)) {
            $this->makeRole($title);
        }
        if ($this->confirm('Do you want create route ? [y|n]', true)) {
            $this->makeRoute($adminController, $siteController, $apiController);
        }
    }

    private function makeModel()
    {
        $this->call('make:model', ['--section' => $this->argument('name'), 'name' => $this->argument('name')]);
    }

    private function makeAdminController()
    {
        $title = $this->ask('What is section title?');
        if (empty($title)) {
            $title = $this->argument('name');
        }
        $this->call('make:controller', [
            '--section' => $this->argument('name'),
            'name'      => ucfirst($this->argument('name')).'Controller',
            '--admin'   => true,
            '--crud'    => true,
            '--model'   => $this->argument('name'),
        ]);
        $this->call('make:view', [
            'section'    => $this->argument('name'),
            'name'       => strtolower($this->argument('name')),
            'title'      => $title,
            'controller' => ucfirst($this->argument('name')).'Controller',
        ]);
    }

    private function makeAdminControllerWithUpload()
    {
        $title = $this->ask('What is section title?');
        if (empty($title)) {
            $title = $this->argument('name');
        }
        $this->call('make:controller', [
            '--section' => $this->argument('name'),
            'name'      => ucfirst($this->argument('name')).'Controller',
            '--upload'  => true,
            '--model'   => $this->argument('name'),
            '--admin'   => true,
        ]);
        $this->call('make:view', [
            'section'    => $this->argument('name'),
            'name'       => strtolower($this->argument('name')),
            'title'      => $title,
            'controller' => ucfirst($this->argument('name')).'Controller',
            '--upload'   => true,
        ]);
    }

    private function makeSiteController()
    {
        $this->call('make:controller', [
            '--section' => ucfirst($this->argument('name')),
            'name'      => ucfirst($this->argument('name')).'Controller',
            '--site'    => true,
        ]);
        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/site/'))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/site/'), 0775, true);
        }
    }

    private function makeMigration($name)
    {
        $this->call('make:migration', [
            '--section' => ucfirst($this->argument('name')),
            'name'      => 'create_'.$name.'_table',
        ]);
    }

    private function makeApiController()
    {
        $this->call('make:controller', [
            '--section'            => ucfirst($this->argument('name')),
            'name'                 => ucfirst($this->argument('name')).'Controller',
            '--api'                => true,
            '--controller-version' => 'v1',
        ]);
    }

    private function makeRequest($adminController, $siteController)
    {
        if ($adminController) {
            $this->call('make:request', [
                '--section' => $this->argument('name'),
                'name'      => ucfirst($this->argument('name')).'Request',
                '--admin'   => true,
            ]);
        }
        if ($siteController) {
            $this->call('make:request', [
                '--section' => $this->argument('name'),
                'name'      => ucfirst($this->argument('name')).'Request',
                '--site'    => true,
            ]);
        }
    }

    private function makeRole($title)
    {
        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.''))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.''), 0775, true);
        }
        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/roles.php'))) {
            $this->error('roles already exists.');
        } else {
            $data = File::get(__DIR__.'/stubs/roles.stub');
            $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{ucFirstname}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{title}}}', $title, $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/roles.php'), $data);
            $this->info('role created successfully.');
        }
    }

    private function makeRoute($adminController, $siteController, $apiController)
    {
        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.'routes'))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.'routes'), 0775, true);
        }
        if ($siteController) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'web.php'))) {
                $this->error('web route already exists.');
            } else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'web.php'), '<?php ');
                $this->info('web route created successfully.');
            }
        }
        if ($adminController) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'admin.php'))) {
                $this->error('admin route already exists.');
            } else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'admin.php'), '<?php ');
                $data = File::get(__DIR__.'/stubs/route-admin.stub');
                $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
                $data = str_replace('{{{controller}}}', ucfirst($this->argument('name')).'Controller', $data);
                $data = str_replace('{{{url}}}', strtolower($this->argument('name')), $data);
                File::append(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'admin.php'), $data);
                $this->info('admin route created successfully.');
            }
        }
        if ($apiController) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'api.php'))) {
                $this->error('api route already exists.');
            } else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/routes/'.'api.php'), '<?php ');
                $this->info('api route created successfully.');
            }
        }
    }

    private function makeFactory()
    {
        $this->call('make:factory', [
            'name'      => ucfirst($this->argument('name')).'Factory',
            '--section' => ucfirst($this->argument('name')),
        ]);
    }
}
