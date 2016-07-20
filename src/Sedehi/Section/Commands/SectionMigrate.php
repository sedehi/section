<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;

class SectionMigrate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate';

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
        $this->call('migrate',
                    ['--path' => 'app/Http/Controllers/Role/database/migrations/']);
        $this->call('migrate', ['--path' => 'app/Http/Controllers/User/database/migrations/']);

        foreach (File::directories(app_path('Http/Controllers')) as $directory) {
            $this->call('migrate',
                        ['--path' => 'app/Http/Controllers/'.File::name($directory).'/database/migrations/']);
        }
    }
}
