<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;
use DB;

class SectionMigrate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';


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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $dirs = array_sort_recursive(File::directories(app_path('Http/Controllers')));

        $bar = $this->output->createProgressBar(count($dirs));

        foreach ($dirs as $directory) {
            $bar->advance();
            $this->info("\n");
            $this->call('migrate', [
                '--path' => 'app/Http/Controllers/'.File::name($directory).'/database/migrations/',
            ]);
        }
        $bar->finish();
        $this->info("\n");

        if ($this->option('seed')) {
            $this->call('db:seed');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
