<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Support\Facades\Schema;

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
        Schema::disableForeignKeyConstraints();

        Schema::defaultStringLength(191);

        $dirs = array_sort_recursive(File::directories(app_path('Http/Controllers')));

        $bar = $this->output->createProgressBar(count($dirs));

        foreach ($dirs as $directory) {
            $bar->advance();
            $this->info("\n");
            $this->call('migrate', [
                '--path' => 'app/Http/Controllers/'.File::name($directory).'/database/migrations/',
                '--force' => true
            ]);
        }
        $bar->finish();
        $this->info("\n");

        if ($this->option('seed')) {
            $this->call('db:seed');
        }

        Schema::enableForeignKeyConstraints();
    }
}
