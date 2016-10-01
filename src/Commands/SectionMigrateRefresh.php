<?php

namespace Sedehi\Section\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;

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
        if (!is_null($this->argument('section'))) {

            Artisan::call('migrate:refresh', [
                '--path'   => 'app/Http/Controllers/'.ucfirst($this->argument('section')).'/database/migrations/',
            ]);

            $this->info(ucfirst($this->argument('section')).' migrations refreshed successfully.');
            return;
        }

        $this->info('All migrations refreshed successfully.');
    }
}
