<?php

namespace Sedehi\Section\Commands;

use Artisan;
use File;
use Illuminate\Console\Command;

class SectionMigration extends Command
{

    use SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migration {section : The name of the section}  {name : The name of the controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file in section';

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
        $this->makeDirectory($this->argument('section'), 'database/migrations/');

        Artisan::call('make:migration', [
            'name'     => $this->argument('name'),
            '--path'   => 'app/Http/Controllers/'.ucfirst($this->argument('section')).'/database/migrations/',
            '--create' => strtolower($this->argument('section'))
        ]);

        $this->info('migration created successfully.');
    }
}
