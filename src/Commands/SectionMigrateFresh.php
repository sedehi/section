<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;

class SectionMigrateFresh extends Command
{

    use SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate-fresh {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and run section:migrate';

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
        $this->laravel['db']->connection(config('database.default'))->getSchemaBuilder()->dropAllTables();

        $this->info('Dropped all tables successfully.');
        $this->info('Running section:migrate ...');
        $this->call('section:migrate');

        if ($this->option('seed')) {
            $this->call('db:seed');
        }

        $this->info('All operations completed successfully.');
    }
}
