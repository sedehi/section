<?php

namespace Sedehi\Section\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Facades\File;

class SectionView extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {section : The name of the section} {name : The name of the folder} {title : The title of the views} {controller : The name of controller} {--upload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin views in section';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $viewPath = 'views/admin/'.strtolower($this->argument('name')).'/';
        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/'.$viewPath))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/'.$viewPath), 0775, true);
        }
        if ($this->option('upload')) {
            foreach (File::files(__DIR__.'/stubs/View/Admin-upload') as $templateFile) {
                if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'))) {
                    $this->error('Admin '.File::name($templateFile).' view already exists.');
                } else {
                    $data = File::get(__DIR__.'/stubs/View/Admin-upload/'.File::name($templateFile));
                    $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
                    $data = str_replace('{{{controller}}}', ucfirst($this->argument('controller')), $data);
                    $data = str_replace('{{{title}}}', $this->argument('title'), $data);
                    $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
                    File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'), $data);
                    $this->info('Admin '.File::name($templateFile).' view created successfully.');
                }
            }
        } else {
            foreach (File::files(__DIR__.'/stubs/View/Admin') as $templateFile) {
                if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'))) {
                    $this->error('Admin '.File::name($templateFile).' view already exists.');
                } else {
                    $data = File::get(__DIR__.'/stubs/View/Admin/'.File::name($templateFile));
                    $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
                    $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
                    $data = str_replace('{{{controller}}}', ucfirst($this->argument('controller')), $data);
                    $data = str_replace('{{{title}}}', $this->argument('title'), $data);
                    File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'), $data);
                    $this->info('Admin '.File::name($templateFile).' view created successfully.');
                }
            }
        }
    }
}
