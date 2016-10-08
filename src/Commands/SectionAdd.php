<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;

class SectionAdd extends Command
{

    use \Illuminate\Console\AppNamespaceDetectorTrait, SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:make {name : The name of the sections}';

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
        $adminController = $siteController = false;
        if ($this->confirm('Do you want create model ? [y|n]', true)) {
            $this->makeModel();
        }
        if ($this->confirm('Do you want create admin controller ? [y|n]', true)) {
            $adminController = true;
            $this->makeDirectory($this->argument('name'), 'views/admin/');

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
        if ($this->confirm('Do you want create form request ? [y|n]', true)) {
            $this->makeRequest($adminController, $siteController);
        }
        $title = $this->ask('What is section title?');
        if (empty($title)) {
            $title = $this->argument('name');
        }
        if ($this->confirm('Do you want create menu ? [y|n]', true)) {
            $this->makeMenu($title);
        }
        if ($this->confirm('Do you want create role ? [y|n]', true)) {
            $this->makeRole($title);
        }
        if ($this->confirm('Do you want create route ? [y|n]', true)) {
            $this->makeRoute();
        }
    }


    private function makeModel()
    {
        $this->call('section:model', ['section' => $this->argument('name'), 'name' => $this->argument('name')]);
    }

    private function makeAdminController()
    {

        $this->call('section:controller', [
            'section'    => $this->argument('name'),
            'name'       => 'adminController',
            '--resource' => true,
        ]);

        foreach (File::files(__DIR__.'/Template/View/Admin') as $templateFile) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/admin/'.File::name($templateFile).'.blade.php'))) {
                $this->error('Admin '.File::name($templateFile).' view already exists.');
            } else {
                $data = File::get(__DIR__.'/Template/View/Admin/'.File::name($templateFile));
                $data = str_replace('{{{section}}}', ucfirst($this->argument('name')), $data);
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/admin/'.File::name($templateFile).'.blade.php'),
                          $data);
                $this->info('Admin '.File::name($templateFile).' view created successfully.');
            }
        }
    }

    private function makeAdminControllerWithUpload()
    {
        $this->call('section:controller', [
            'section'    => $this->argument('name'),
            'name'       => 'adminController',
            '--upload'   => true,
            '--resource' => true,
        ]);

        foreach (File::files(__DIR__.'/Template/View/Admin-upload') as $templateFile) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/admin/'.File::name($templateFile).'.blade.php'))) {
                $this->error('Admin '.File::name($templateFile).' view already exists.');
            } else {
                $data = File::get(__DIR__.'/Template/View/Admin-upload/'.File::name($templateFile));
                $data = str_replace('{{{section}}}', ucfirst($this->argument('name')), $data);
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/admin/'.File::name($templateFile).'.blade.php'),
                          $data);
                $this->info('Admin '.File::name($templateFile).' view created successfully.');
            }
        }
    }

    private function makeSiteController()
    {
        $this->call('section:controller', [
            'section' => ucfirst($this->argument('name')),
            'name'    => 'SiteController',
            '--plain' => true
        ]);
        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/site/'))) {

            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/views/site/'), 0775,
                                true);
        }
    }

    private function makeRequest($adminController, $siteController)
    {
        if ($adminController) {
            $this->call('section:request', [
                'section' => $this->argument('name'),
                'name'    => 'AdminRequest',
                '--admin' => true,
            ]);
        }

        if ($siteController) {
            $this->call('section:request', [
                'section' => $this->argument('name'),
                'name'    => 'SiteRequest',
            ]);
        }
    }

    private function makeMenu($title)
    {
        $this->makeDirectory($this->argument('name'));

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/menu.php'))) {
            $this->error('menu already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/menu');
            $data = str_replace('{{{title}}}', $title, $data);
            $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{Classname}}}', ucfirst($this->argument('name')), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/menu.php'), $data);
            $this->info('Menu created successfully.');
        }
    }

    private function makeRole($title)
    {
        $this->makeDirectory($this->argument('name'));
        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/roles.php'))) {
            $this->error('roles already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/roles');
            $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{title}}}', $title, $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/roles.php'), $data);
            $this->info('role created successfully.');
        }
    }

    private function makeRoute()
    {

        $this->makeDirectory($this->argument('name'));

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.'route.php'))) {
            $this->error('routes already exists.');
        } else {
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.'route.php'), '<?php ');
            $data = File::get(__DIR__.'/Template/routeAdmin');
            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{url}}}', strtolower($this->argument('name')), $data);
            File::append(app_path('Http/Controllers/'.ucfirst($this->argument('name')).'/'.'route.php'), $data);

            $this->info('routes created successfully.');
        }
    }


}
