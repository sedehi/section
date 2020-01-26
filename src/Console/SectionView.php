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
    protected $signature = 'make:view {section : The name of the section} {name : The name of the folder} {title : The title of the views} {controller : The name of controller} {--upload} {--custom}';

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
        $stubFolder = $this->option('custom') ? 'custom' : 'dynamic';
        if ($this->option('upload')) {
            $stubPath = __DIR__.'/stubs/views/'.$stubFolder.'/with-upload/';
            foreach (File::files($stubPath) as $templateFile) {
                if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'))) {
                    $this->error('Admin '.File::name($templateFile).' view already exists.');
                } else {
                    if (File::exists(resource_path('section-stubs/'.$stubFolder.'/with-upload/'.File::name($templateFile).'.stub'))) {
                        $data = File::get(resource_path('section-stubs/'.$stubFolder.'/with-upload/'.File::name($templateFile).'.stub'));
                    } else {
                        $data = File::get($stubPath.File::name($templateFile));
                    }
                    $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
                    $data = str_replace('{{{sectionLower}}}', strtolower($this->argument('section')), $data);
                    $data = str_replace('{{{controller}}}', ucfirst($this->argument('controller')), $data);
                    $data = str_replace('{{{controllerLower}}}', strtolower($this->argument('controller')), $data);
                    $data = str_replace('{{{title}}}', $this->argument('title'), $data);
                    $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
                    File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'), $data);
                    $this->info('Admin '.File::name($templateFile).' view created successfully.');
                }
            }
        } else {
            $stubPath = __DIR__.'/stubs/views/'.$stubFolder.'/';
            foreach (File::files($stubPath) as $templateFile) {
                if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'))) {
                    $this->error('Admin '.File::name($templateFile).' view already exists.');
                } else {
                    if (File::exists(resource_path('section-stubs/'.$stubFolder.'/'.File::name($templateFile).'.stub'))) {
                        $data = File::get(resource_path('section-stubs/'.$stubFolder.'/'.File::name($templateFile).'.stub'));
                    } else {
                        $data = File::get($stubPath.File::name($templateFile));
                    }
                    $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
                    $data = str_replace('{{{sectionLower}}}', strtolower($this->argument('section')), $data);
                    $data = str_replace('{{{name}}}', strtolower($this->argument('name')), $data);
                    $data = str_replace('{{{controller}}}', ucfirst($this->argument('controller')), $data);
                    $data = str_replace('{{{controllerLower}}}', strtolower($this->argument('controller')), $data);
                    $data = str_replace('{{{title}}}', $this->argument('title'), $data);
                    File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/views/admin/'.strtolower($this->argument('name')).'/'.File::name($templateFile).'.blade.php'), $data);
                    $this->info('Admin '.File::name($templateFile).' view created successfully.');
                }
            }
        }
    }
}
