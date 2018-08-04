<?php

namespace Sedehi\Section\Commands;

use Artisan;
use Illuminate\Database\Console\Migrations\BaseCommand;

class SectionMigration extends BaseCommand
{

    use SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migration {section : The name of the section} 
                                              {name : The name of the migration file}
                                              {--create= : The table to be created}
                                              {--table= : The table to migrate}';

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

        $create = is_null($this->option('create')) ? strtolower($this->argument('section')) : $this->option('create');

        $table = strtolower($this->option('table'));

        Artisan::call('make:migration', [
            'name'     => $this->argument('name'),
            '--path'   => 'app/Http/Controllers/'.ucfirst($this->argument('section')).'/database/migrations/',
            '--create' => (!$table) ? $create : null,
            '--table'  => $table
        ]);

        $this->info('migration created successfully.');
    }
}
