<?php

namespace Sedehi\Section\Commands;

use File;
use Illuminate\Console\Command;
use Sedehi\Http\Controllers\Role\Models\Role;

class SectionUpdateRoles extends Command
{

    use \Illuminate\Console\AppNamespaceDetectorTrait, SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:update-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update all roles in database';

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
        $data = ['filemanager' => 1];
        foreach (adminRole() as $section => $role) {
            foreach ($role['access'] as $controller => $methods) {
                foreach ($methods as $method) {
                    if (is_array($method)) {
                        $finalMethod = strtolower(implode(',', $method));
                    } else {
                        $finalMethod = strtolower($method);
                    }
                    $finalMethods[$finalMethod] = 1;
                }
                $data[strtolower($section)] = [
                    strtolower($controller) => $finalMethods
                ];
                $finalMethods               = [];
            }
        }
        $role = Role::find(1);
        if (!is_null($role)) {
            $role->permission = serialize($data);
            $role->save();
        }
    }
}
