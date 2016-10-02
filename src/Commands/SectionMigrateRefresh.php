<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SectionMigrateRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate-refresh {section? : The name of the section}';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call('section:migrate-reset');
        $this->info('Running section:migrate ...');
        $this->call('section:migrate');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
