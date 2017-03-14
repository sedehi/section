<?php

namespace Sedehi\Section\Commands;

use File;
use Illuminate\Console\Command;
use ReflectionObject;

class SectionPicture extends Command
{

    use \Illuminate\Console\AppNamespaceDetectorTrait, SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:picture {section : The name of the section || All For All Sections}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create pictures';

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
        foreach (File::directories(app_path('Http/Controllers')) as $directory) {

            if (File::isDirectory($directory.'/Controllers/Admin')) {

                foreach (File::allFiles($directory.'/Controllers') as $controllerPath) {

                    foreach (File::allFiles($controllerPath->getPath()) as $controller) {

                        $section         = explode('/', $directory);
                        $section         = end($section);
                        $folder          = explode('/', $controller->getPath());
                        $folder          = end($folder);
                        $controllerClass = '\Sedehi\Http\Controllers\\'.$section.'\Controllers\\'.$folder.'\\'.File::name($controller->getBasename());
                        $object          = app($controllerClass);
                        $ref             = new ReflectionObject($object);

                        if ($ref->hasProperty('uploadPath') && $ref->hasProperty('imageSize')) {
                            if ($ref->getProperty('uploadPath')->isPublic() && $ref->getProperty('imageSize')
                                                                                   ->isPublic()
                            ) {
                                $uploadPath = $ref->getProperty('uploadPath')->getValue($object);
                                if (File::isDirectory(public_path($uploadPath))) {
                                    foreach (File::allFiles(public_path($uploadPath)) as $file) {
                                        $filename = explode('-', $file->getFilename());
                                        if (count($filename) > 1) {

                                            if ($uploadPath == 'uploads/product/') {
                                                dd($filename);
                                            }

                                            if (File::exists(public_path($uploadPath.$file->getFilename()))) {
                                                File::delete(public_path($uploadPath.$file->getFilename()));
                                            }
                                        }
                                    }
                                    foreach (File::allFiles(public_path($uploadPath)) as $file) {
                                        $object->createImages($file, $file->getFileName());
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
