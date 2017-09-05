<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionAuth extends Command
{

    use DetectsApplicationNamespace, SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auth controllers and views';

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
        $this->info('');
        $this->info('Available Choices :');
        $this->info('');
        $this->info('1 => Email Only');
        $this->info('2 => Email And Mobile');
        $this->info('3 => Email And Username');

        $type = null;

        while (!in_array($type,[1,2,3]))
        {
            $type = $this->ask('What type of auth do you want ? ');
        }

        switch ($type) {
            case 1:

                $this->createController('LoginController','email-only');
                $this->createController('RegisterController','email-only');
                $this->createController('ReminderController','email-only');
                $this->createMail();
                $this->createRequest('AuthRequest','email-only');
                $this->createRequest('ReminderRequest','email-only');
                $this->createView('login','email-only');
                $this->createView('reminder','email-only');
                $this->createView('reset-password','email-only');
                $this->createView('signup','email-only');
                $this->createRoute();

                break;
            case 2:

                $this->createController('LoginController','email-mobile');
                $this->createController('RegisterController','email-mobile');
                $this->createController('ReminderController','email-only');
                $this->createMail();
                $this->createRequest('AuthRequest','email-mobile');
                $this->createRequest('ReminderRequest','email-only');
                $this->createView('login','email-mobile');
                $this->createView('reminder','email-only');
                $this->createView('reset-password','email-only');
                $this->createView('signup','email-mobile');
                $this->createRoute();

                break;
            case 3:

                $this->createController('LoginController','email-username');
                $this->createController('RegisterController','email-username');
                $this->createController('ReminderController','email-only');
                $this->createMail();
                $this->createRequest('AuthRequest','email-username');
                $this->createRequest('ReminderRequest','email-only');
                $this->createView('login','email-username');
                $this->createView('reminder','email-only');
                $this->createView('reset-password','email-only');
                $this->createView('signup','email-username');
                $this->createRoute();

                break;
        }
    }

    private function createController($controller, $folder)
    {
        if (File::exists(app_path('Http/Controllers/Auth/Controllers/Site/'.$controller.'.php'))) {
            $this->warn($controller.' already exists.');
        } else {
            $this->makeDirectory('Auth','Controllers/Site');
            $data = File::get(__DIR__.'/Template/auth/'.$folder.'/controller/'.$controller.'.stub');
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/Auth/Controllers/Site/'.$controller.'.php'),$data);
            $this->info($controller.' created successfully.');
        }
    }

    private function createMail()
    {
        if (File::exists(app_path('Http/Controllers/Auth/Mail/PasswordReminderMail.php'))) {
            $this->warn('PasswordReminderMail already exists.');
        } else {
            $this->makeDirectory('Auth','Mail');
            $data = File::get(__DIR__.'/Template/auth/email-only/mail/PasswordReminderMail.stub');
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/Auth/Mail/PasswordReminderMail.php'),$data);
            $this->info('PasswordReminderMail created successfully.');
        }
    }

    private function createRequest($request, $folder)
    {
        if (File::exists(app_path('Http/Controllers/Auth/Requests/Site/'.$request.'.php'))) {
            $this->warn($request.' already exists.');
        } else {
            $this->makeDirectory('Auth','Requests/Site');
            $data = File::get(__DIR__.'/Template/auth/'.$folder.'/request/site/'.$request.'.stub');
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/Auth/Requests/Site/'.$request.'.php'),$data);
            $this->info($request.' created successfully.');
        }
    }

    private function createView($viewName, $folder)
    {
        $this->makeDirectory('Auth','views/site');

        if (File::exists(app_path('Http/Controllers/Auth/views/site/'.$viewName.'.blade.php'))) {
            $this->warn($viewName.'.blade.php already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/auth/'.$folder.'/view/site/'.$viewName.'.stub');
            File::put(app_path('Http/Controllers/Auth/views/site/'.$viewName.'.blade.php'),$data);
            $this->info($viewName.'.blade.php created successfully.');
        }
    }

    private function createRoute()
    {
        $webRoutePath = app_path('Http/Controllers/Auth/routes/web.php');

        if (File::exists($webRoutePath)) {
            $data = File::get(__DIR__.'/Template/auth/route.stub');

            $written = File::append($webRoutePath,"\n\n");

            if ($written === false) {
                $this->error('Can\'t write to web.php file');
            }

            File::append($webRoutePath,$data);

            $this->info('Auth routes added to web.php successfully.');
        } else {
            $this->makeDirectory('Auth','routes');
            $data = File::get(__DIR__.'/Template/auth/route.stub');
            File::put(app_path('Http/Controllers/Auth/routes/web.php'),"<?php\n\n".$data);
            $this->info('Route file created successfully.');
        }
    }
}
