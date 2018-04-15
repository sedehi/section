<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Schema;

class SectionMigrateRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate-refresh  {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh one section or all migrations';

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

        $this->call('section:migrate-reset');
        $this->info('Running section:migrate ...');
        $this->call('section:migrate');

        if ($this->option('seed')) {
            $this->call('db:seed');
        }

        Schema::enableForeignKeyConstraints();
    }
}
